<?php
/**
 * @copyright 2018 interactivesolutions
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

namespace Tests\Feature\Controllers;

use HoneyComb\Core\Events\Admin\HCUserCreated;
use HoneyComb\Core\Events\Admin\HCUserUpdated;
use HoneyComb\Core\Models\HCUser;
use HoneyComb\Core\Notifications\HCActivationLink;
use HoneyComb\Core\Notifications\HCAdminWelcomeEmail;
use HoneyComb\Core\Services\HCUserService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/**
 * Class HCUserServiceTest
 * @package Tests\Feature\Controllers
 */
class HCUserServiceTest extends TestCase
{
    use RefreshDatabase, InteractsWithDatabase;

    /**
     * @test
     * @group userService
     */
    public function it_must_create_singleton_instance(): void
    {
        $this->assertInstanceOf(HCUserService::class, $this->getTestClassInstance());

        $this->assertSame($this->getTestClassInstance(), $this->getTestClassInstance());
    }

    /**
     * @test
     * @group userService
     */
    public function it_must_create_user_without_welcome_email(): void
    {
        $userData = [
            'email' => 'hello@bum.lt',
            'password' => '123456789',
            'is_active' => 0,
        ];

        $roles = [];
        $personalData = [];

        $initialDispatcher = Event::getFacadeRoot();

        Event::fake();
        Notification::fake();
        Model::setEventDispatcher($initialDispatcher);

        /** @var HCUser $userRecord */
        $userRecord = $this->getTestClassInstance()->createUser($userData, $roles, $personalData, false, false);

        Event::assertDispatched(HCUserCreated::class, function ($e) use ($userRecord) {
            return $e->user->id === $userRecord->id;
        });

        // Assert a notification was not sent...
        Notification::assertNotSentTo(
            [$userRecord], HCAdminWelcomeEmail::class
        );
        Notification::assertSentTo(
            [$userRecord], HCActivationLink::class
        );

        $this->assertInstanceOf(HCUser::class, $userRecord);

        $this->assertDatabaseHas('hc_user', [
            'email' => 'hello@bum.lt',
            'activated_at' => null,
        ]);
    }

    /**
     * @test
     * @group userService
     */
    public function it_must_create_user_with_sending_welcome_email(): void
    {
        $userData = [
            'email' => 'hello@bum.lt',
            'password' => '123456789',
            'is_active' => 0,
        ];

        $roles = [];
        $personalData = [];

        $initialDispatcher = Event::getFacadeRoot();

        Notification::fake();

        Event::fake();

        Model::setEventDispatcher($initialDispatcher);

        /** @var HCUser $userRecord */
        $userRecord = $this->getTestClassInstance()->createUser($userData, $roles, $personalData, true, true);

        Notification::assertSentTo($userRecord, HCAdminWelcomeEmail::class,
            function ($notification) use ($userRecord) {
                $message = $notification->toMail($userRecord)->toArray();

                $this->assertContains('You have successfully registered!', $message['subject']);
                $this->assertContains('Congratulations!', $message['greeting']);

                return true;
            });

        Event::assertDispatched(HCUserCreated::class, function ($e) use ($userRecord) {
            return $e->user->id === $userRecord->id;
        });

        $this->assertInstanceOf(HCUser::class, $userRecord);

        $this->assertDatabaseHas('hc_user', [
            'email' => 'hello@bum.lt',
            'activated_at' => null,
        ]);

//        Mail::assertSent(HCAdminWelcomeEmail::class, 2);
    }

    /**
     *
     * @group u
     */
    public function it_must_update_user(): void
    {
        $userData = [
            'email' => 'hello@bum.lt',
            'password' => '123456789',
        ];

        $userId = '1';

        $roles = [];
        $personalData = [];

        $initialDispatcher = Event::getFacadeRoot();

        Event::fake();
        Model::setEventDispatcher($initialDispatcher);

        /** @var HCUser $userRecord */
        $userRecord = $this->getTestClassInstance()->updateUser($userId, $userData, $roles, $personalData);

        Event::assertDispatched(HCUserUpdated::class, function ($e) use ($userRecord) {

            return $e->user->id === $userRecord->id;
        });

        $this->assertInstanceOf(HCUser::class, $userRecord);
        $this->assertDatabaseHas('hc_user', array_except($userData, 'password'));

    }


    /**
     * @return HCUserService
     */
    private function getTestClassInstance(): HCUserService
    {
        return $this->app->make(HCUserService::class);
    }


}
