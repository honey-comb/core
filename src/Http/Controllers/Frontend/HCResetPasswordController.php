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

use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use HoneyComb\Core\Helpers\HCFrontendResponse;
use HoneyComb\Core\Http\Controllers\HCBaseController;

/**
 * Class HCResetPasswordController
 * @package InteractiveSolutions\HoneycombAcl\Http\Controllers
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
     * @var HCFrontendResponse
     */
    private $response;

    /**
     * Create a new controller instance.
     * @param HCFrontendResponse $response
     */
    public function __construct(HCFrontendResponse $response)
    {
        $this->middleware('guest');

        $this->response = $response;
    }

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param Request $request
     * @param string|null $token
     * @return View
     */
    public function showResetForm(Request $request, $token = null): View
    {
        return view('HCCore::password.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }


    /**
     * Get the response for a successful password reset.
     *
     * @param string $response
     * @return JsonResponse
     * @throws \Illuminate\Container\EntryNotFoundException
     */
    protected function sendResetResponse($response): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => trans($response),
            'redirectURL' => session('url.intended', url('/')),
        ]);
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
}
