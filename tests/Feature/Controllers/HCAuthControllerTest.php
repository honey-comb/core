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
 * Class HCAuthControllerTest
 * @package Tests\Feature\Controllers
 */
class HCAuthControllerTest extends TestCase
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
     * @group auth
     * @throws \Exception
     */
    public function it_must_register_new_user(): void
    {
        $userData = [
            'email' => 'hello@gmail.com',
            'password' => '123456789',
            'is_active' => '0',
        ];

        $response = $this->json('POST', route('v1.api.register'), $userData);

        $response->assertResponseOk();

        $response->seeJsonEquals([
            'success' => true,
        ]);
    }

    /**
     * Move test to project
     *
     * @group auth
     * @throws \Exception
     */
    public function it_must_fail_to_register_new_user_if_password_is_not_set(): void
    {
        $userData = [
            'email' => 'hello@gmail.com',
        ];

        $response = $this->json('POST', route('v1.api.register'), $userData);

        $response->seeJsonEquals([
            'message' => 'The given data was invalid.',
            'errors' => [
                'password' => ['The password field is required.'],
            ],
        ]);

        $response->assertResponseStatus(422);
    }

    /**
     * Move test to project
     *
     * @group auth
     * @throws \Exception
     */
    public function it_must_fail_to_register_new_user_if_mail_is_not_set(): void
    {
        $userData = [
            'password' => '123456789',
        ];

        $response = $this->json('POST', route('v1.api.register'), $userData);

        $response->seeJsonEquals([
            'message' => 'The given data was invalid.',
            'errors' => [
                'email' => ['The email field is required.'],
            ],
        ]);

        $response->assertResponseStatus(422);
    }

    /**
     * Move test to project
     *
     * @group auth
     * @throws \Exception
     */
    public function it_must_login_user(): void
    {
        $expectedUser = factory(HCUser::class)->create();

        $userData = [
            'provider' => 'email',
            'email' => $expectedUser->email,
            'password' => 'secret',
        ];

        config(['auth.providers.users.model' => HCUser::class]);

        $response = $this->json('POST', route('v1.api.login'), $userData);
        $response->assertResponseOk();
        $response->seeJsonEquals([
            'success' => true,
            'data' => null,
            'message' => 'OK',
        ]);
    }

    /**
     * Move test to project
     *
     * @group auth
     * @throws \Exception
     */
    public function it_must_fail_to_login_user_with_wrong_password(): void
    {
        $expectedUser = factory(HCUser::class)->create();

        $userData = [
            'provider' => 'email',
            'email' => $expectedUser->email,
            'password' => 'wrongPassword',
        ];

        config(['auth.providers.users.model' => HCUser::class]);

        $response = $this->json('POST', route('v1.api.login'), $userData);
        $response->assertResponseStatus(400);
        $response->seeJsonEquals([
            'success' => false,
            'data' => null,
            'message' => trans('HCCore::users.error.auth_bad_credentials'),
        ]);
    }

    /**
     * Move test to project
     *
     * @group auth
     * @throws \Exception
     */
    public function it_must_fail_to_login_user_with_wrong_mail(): void
    {
        factory(HCUser::class)->create();

        $userData = [
            'provider' => 'email',
            'email' => 'wrongMail@mail.com',
            'password' => 'secret',
        ];

        config(['auth.providers.users.model' => HCUser::class]);

        $response = $this->json('POST', route('v1.api.login'), $userData);
        $response->assertResponseStatus(400);

        $response->seeJsonEquals([
            'success' => false,
            'message' => trans('HCCore::users.error.auth_bad_credentials'),
        ]);
    }

    /**
     * Move test to project
     *
     * @group auth
     */
    public function it_must_logout_user(): void
    {
        $user = factory(HCUser::class)->create();

        $this->actingAs($user);

        config(['auth.providers.users.model' => HCUser::class]);

        $response = $this->json('GET', route('v1.api.logout'));

        $response->assertRedirectedTo('/');
        $response->assertResponseStatus(302);
    }
}
