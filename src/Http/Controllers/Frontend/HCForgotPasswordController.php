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

use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\View\View;
use HoneyComb\Core\Helpers\HCFrontendResponse;
use HoneyComb\Core\Http\Controllers\HCBaseController;

/**
 * Class HCForgotPasswordController
 * @package HoneyComb\Core\Http\Controllers\Frontend
 */
class HCForgotPasswordController extends HCBaseController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

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
     * Display the form to request a password reset link.
     * @return View
     */
    public function showLinkRequestForm(): View
    {
        return view('HCCore::password.remind');
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param  string $response
     * @return string
     */
    protected function sendResetLinkResponse($response)
    {
        return $this->response->success(trans($response));
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param Request $request
     * @param $response
     * @return string;
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return $this->response->error(trans($response));
    }
}
