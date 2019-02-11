<?php
/**
 * @copyright 2019 innovationbase
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
 * Contact InnovationBase:
 * E-mail: hello@innovationbase.eu
 * https://innovationbase.eu
 */

declare(strict_types = 1);

namespace HoneyComb\Core\Http\Controllers\Password;

use HoneyComb\Core\DTO\HCAuthorizeDTO;
use HoneyComb\Core\Http\Controllers\HCBaseController;
use HoneyComb\Core\Models\HCUser;
use HoneyComb\Core\Services\HCMenuService;
use HoneyComb\Starter\Helpers\HCResponse;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Class HCResetPasswordController
 * @package HoneyComb\Core\Http\Controllers\Password
 */
class HCResetPasswordController extends HCBaseController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * @var HCUser
     */
    protected $user;

    /**
     * @var HCResponse
     */
    protected $response;
    
    /**
     * @var HCMenuService
     */
    private $menuService;

    /**
     * Create a new controller instance.
     * @param HCResponse $response
     * @param HCMenuService $menuService
     */
    public function __construct(HCResponse $response, HCMenuService $menuService)
    {
        $this->middleware('guest');

        $this->response = $response;
        $this->menuService = $menuService;
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword $user
     * @param  string $password
     * @return void
     * @throws \Exception
     */
    protected function resetPassword($user, $password)
    {
        $user->password = Hash::make($password);

        $user->setRememberToken(Str::random(60));

        $user->save();

        event(new PasswordReset($user));

        $this->user = $user;
    }

    /**
     * Get the response for a successful password reset.
     *
     * @param Request $request
     * @param string $response
     * @return JsonResponse
     * @throws \Exception
     */
    protected function sendResetResponse(Request $request, $response): JsonResponse
    {
        $user = (new HCAuthorizeDTO(
            $this->user,
            $this->user->createToken('Personal Access Token'),
            $this->getUserConfig($this->user)
        ))->toArray();

        return $this->response->success(trans($response), $user);
    }

    /**
     * Get the response for a failed password reset.
     *
     * @param Request $request
     * @param string $response
     * @return JsonResponse
     */
    protected function sendResetFailedResponse(Request $request, $response): JsonResponse
    {
        return $this->response->error(trans($response));
    }

    /**
     * @param HCUser $user
     * @return array
     * @throws \Exception
     */
    protected function getUserConfig(HCUser $user): array
    {
        return [
            'menu' => $this->menuService->getList($user),
        ];
    }
}
