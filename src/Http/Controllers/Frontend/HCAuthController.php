<?php
/**
 * @copyright 2017 interactivesolutions
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * Contact InteractiveSolutions:
 * E-mail: hello@interactivesolutions.lt
 * http://www.interactivesolutions.lt
 */

declare(strict_types = 1);

namespace HoneyComb\Core\Http\Controllers\Frontend;

use Illuminate\Database\Connection;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use HoneyComb\Core\Helpers\HCFrontendResponse;
use HoneyComb\Core\Http\Controllers\HCBaseController;
use HoneyComb\Core\Http\Requests\HCUserRequest;
use HoneyComb\Core\Services\HCUserActivationService;
use HoneyComb\Core\Services\HCUserService;

/**
 * Class HCAuthController
 * @package HoneyComb\Core\Http\Controllers
 */
class HCAuthController extends HCBaseController
{
    use AuthenticatesUsers;

    /**
     * Max login attempts
     *
     * @var int
     */
    protected $maxLoginAttempts = 5;

    /**
     * The number of minutes to delay further login attempts.
     *
     * @var int
     */
    protected $lockoutTime = 1;

    /**
     * Redirect url
     *
     * @var
     */
    protected $redirectUrl;

    /**
     * @var HCUserActivationService
     */
    private $activation;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var HCFrontendResponse
     */
    private $response;

    /**
     * @var HCUserService
     */
    private $userService;

    /**
     * AuthController constructor.
     * @param Connection $connection
     * @param HCUserActivationService $activation
     * @param HCFrontendResponse $response
     * @param HCUserService $userService
     */
    public function __construct(
        Connection $connection,
        HCUserActivationService $activation,
        HCFrontendResponse $response,
        HCUserService $userService
    ) {
        $this->connection = $connection;
        $this->activation = $activation;
        $this->response = $response;
        $this->userService = $userService;
    }


    /**
     * Displays users login form
     *
     * @return View
     */
    public function showLoginForm(): View
    {
        $config = [];

        return view('HCCore::auth.login', $config);
    }

    /**
     * Function which login users
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Container\EntryNotFoundException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        if (!$this->attemptLogin($request)) {
            return $this->response->error(trans('HCCore::user.errors.login'));
        }

        // check if user is not activated
        if (auth()->user()->isNotActivated()) {
            $user = auth()->user();

            $this->logout($request);

            $response = $this->activation->sendActivationMail($user);

            return $this->response->error($response);
        }

        auth()->user()->updateLastLogin();

        return $this->response->success(
            'Success',
            null,
            session()->pull('url.intended', url('/'))
        );
    }

    /**
     * Display users register form
     *
     * @return \Illuminate\Contracts\View\Factory|RedirectResponse|\Illuminate\Routing\Redirector|View
     * @throws \Illuminate\Container\EntryNotFoundException
     */
    public function showRegister()
    {
        if (!config('hc.allow_registration')) {
            return redirect('/');
        }

        return view('HCCore::auth.register');
    }

    /**
     * User registration
     *
     * @param HCUserRequest $request
     * @return JsonResponse
     * @throws \Exception
     * @throws \Illuminate\Container\EntryNotFoundException
     */
    public function register(HCUserRequest $request): JsonResponse
    {
        if (!config('hc.allow_registration')) {
            throw new \Exception();
        }

        $this->connection->beginTransaction();

        try {
            $this->userService->createUser($request->getInputData());
        } catch (\Exception $exception) {
            $this->connection->rollback();

            return $this->response->error($exception->getMessage());
        }

        $this->connection->commit();

        session(['activation_message' => trans('HCCore::user.activation.activate_account')]);

        if ($this->redirectUrl) {
            return response(['success' => true, 'redirectUrl' => $this->redirectUrl]);
        } else {
            return response(['success' => true, 'redirectUrl' => route('auth.login')]);
        }
    }

    /**
     * Logout function
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        $this->guard()->logout();

        $request->session()->flush();

        $request->session()->regenerate();

        return redirect('/')->with('flash_notice', trans('HCCore::user.success.logout'));
    }

    /**
     * Show activation page
     *
     * @param string $token
     * @return View
     */
    public function showActivation(string $token): View
    {
        $message = null;

        $tokenRecord = DB::table('hc_user_activations')->where('token', $token)->first();

        if (is_null($tokenRecord)) {
            $message = trans('HCCore::user.activation.token_not_exists');
        } elseif (strtotime($tokenRecord->created_at) + 60 * 60 * 24 < time()) {
            $message = trans('HCCore::user.activation.token_expired');
        }

        return view('HCCore::auth.activation', ['token' => $token, 'message' => $message]);
    }

    /**
     * Active user account
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function activate(Request $request): RedirectResponse
    {
        $this->connection->beginTransaction();

        try {
            $this->activation->activateUser(
                $request->input('token')
            );
        } catch (\Exception $e) {
            $this->connection->rollback();

            return redirect()->back()->withErrors($e->getMessage());
        }

        $this->connection->commit();

        return redirect()->intended();
    }

    /**
     * Determine if the user has too many failed login attempts.
     *
     * @param Request $request
     * @return bool
     */
    protected function hasTooManyLoginAttempts(Request $request): bool
    {
        return $this->limiter()->tooManyAttempts(
            $this->throttleKey($request), $this->maxLoginAttempts, $this->lockoutTime
        );
    }


    /**
     * Redirect the user after determining they are locked out.
     *
     * @param Request $request
     * @return JsonResponse
     */
    protected function sendLockoutResponse(Request $request): JsonResponse
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        return $this->response->error(
            trans('auth.throttle', ['seconds' => $seconds]),
            null,
            JsonResponse::HTTP_LOCKED
        );
    }
}
