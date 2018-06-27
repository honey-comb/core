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

use HoneyComb\Core\Events\frontend\HCUserActivated;
use HoneyComb\Core\Http\Controllers\HCBaseController;
use HoneyComb\Core\Http\Requests\Frontend\HCAuthRequest;
use HoneyComb\Core\Models\HCUser;
use HoneyComb\Core\Services\HCUserActivationService;
use HoneyComb\Core\Services\HCUserService;
use HoneyComb\Starter\Helpers\HCFrontendResponse;
use Illuminate\Database\Connection;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class HCAuthController
 * @package HoneyComb\Core\Http\Controllers\Frontend
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
    protected $activation;

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var HCFrontendResponse
     */
    protected $response;

    /**
     * @var HCUserService
     */
    protected $userService;

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
     * @param HCAuthRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function register(HCAuthRequest $request): JsonResponse
    {
        if (!config('hc.allow_registration')) {
            throw new \Exception('Can\'t register');
        }

        $this->connection->beginTransaction();

        try {
            /** @var HCUser $user */
            $this->userService->createUser(
                $request->getInputData(),
                $request->getRoles()
            );

        } catch (\Throwable $exception) {
            $this->connection->rollback();

            report($exception);

            return $this->response->error($exception->getMessage());
        }

        $this->connection->commit();

        session(['activation_message' => trans('HCCore::user.activation.activate_account')]);

        return response()->json([
            'success' => true,
            'redirectUrl' => $this->redirectUrl ?? route('auth.login'),
        ]);
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
     * @param Request $request
     * @param string $token
     * @return \Illuminate\Contracts\View\Factory|RedirectResponse|\Illuminate\Routing\Redirector|View
     * @throws \Exception
     */
    public function showActivation(Request $request, string $token)
    {
        $this->connection->beginTransaction();

        try {
            $this->activation->activateUser($token);
            $this->connection->commit();

        } catch (\Throwable $exception) {
            $this->connection->rollBack();

            report($exception);

            return view('HCCore::auth.activation', ['token' => $token, 'message' => $exception->getMessage()]);
        }

        return redirect($request->url());
    }

    /**
     * Active user account
     * @param Request $request
     * @return RedirectResponse
     * @throws \Exception
     */
    public function activate(Request $request): RedirectResponse
    {
        $this->connection->beginTransaction();

        try {
            $user = $this->activation->activateUser(
                $request->input('token')
            );
        } catch (\Throwable $exception) {
            $this->connection->rollback();

            report($exception);

            return redirect()->back()->withErrors($exception->getMessage());
        }

        event(new HCUserActivated($user));

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
