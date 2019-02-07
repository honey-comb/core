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

namespace HoneyComb\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class HCAuthLoginRequest
 * @package HoneyComb\Core\Http\Requests
 */
class HCAuthLoginRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function isEmailProvider(): bool
    {
        return $this->input('provider') == 'email';
    }

    /**
     * @return bool
     */
    public function isFacebookProvider(): bool
    {
        return $this->input('provider') == 'facebook';
    }

    /**
     * @return bool
     */
    public function isGoogleProvider(): bool
    {
        return $this->input('provider') == 'google';
    }

    /**
     * @return bool
     */
    public function isSocialProvider(): bool
    {
        return in_array($this->input('provider'), ['facebook', 'google', 'linkedin']);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        if ($this->input('provider') == 'email') {
            return [
                'provider' => 'required|in:email',
                'email' => 'required|string|email|exists:hc_user,email',
                'password' => 'required|string',
            ];
        }

        return [
            'provider' => 'required|in:facebook,google,linkedin',
            'access_token' => 'required|string',
        ];
    }
}
