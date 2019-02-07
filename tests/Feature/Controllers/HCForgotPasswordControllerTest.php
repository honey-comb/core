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

namespace Tests\Feature\Controllers;

use HoneyComb\Core\Models\HCUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class HCForgotPasswordControllerTest
 * @package Tests\Feature\Controllers
 */
class HCForgotPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @group auth
     * @throws \Exception
     */
    public function it_must_return_true(): void
    {
        $this->assertTrue(true);
    }

    /**
     * Move test to project
     *
     * @group forgotPassword
     * @throws \Illuminate\Container\EntryNotFoundException
     */
    public function it_must_send_reset_link_response_with_correct_email(): void
    {
        $expectedUser = factory(HCUser::class)->create();

        $userMail = [
            'email' => $expectedUser->email,
        ];

        config(['auth.providers.users.model' => HCUser::class]);

        config(['auth.passwords.users.table' => 'hc_user_password_reset']);

        $response = $this->json('POST', route('users.password.remind.post'), $userMail);
        $response->assertResponseOk();
        $response->seeJsonEquals([
            'success' => true,
            'redirectUrl' => null,
            'data' => null,
            'message' => 'We have e-mailed your password reset link!',
        ]);
    }

    /**
     * Move test to project
     *
     * @group forgotPassword
     * @throws \Illuminate\Container\EntryNotFoundException
     */
    public function it_must_not_send_reset_link_response_with_incorrect_email(): void
    {
        factory(HCUser::class)->create();

        $userMail = [
            'email' => 'wrongMail@mail.com',
        ];

        config(['auth.providers.users.model' => HCUser::class]);

        $response = $this->json('POST', route('users.password.remind.post'), $userMail);
        $response->assertResponseStatus(400);
        $response->seeJsonEquals([
            'success' => false,
            'data' => null,
            'message' => 'We can\'t find a user with that e-mail address.',
        ]);
    }
}
