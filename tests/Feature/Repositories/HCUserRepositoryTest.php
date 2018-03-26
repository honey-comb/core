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

namespace Tests\Feature\Repositories;

use HoneyComb\Core\Models\HCUser;
use HoneyComb\Core\Repositories\HCUserRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class HCUserRepositoryTest
 * @package Tests\Feature\Repositories
 */
class HCUserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @group user
     */
    public function it_must_create_singleton_instance(): void
    {
        $this->assertInstanceOf(HCUserRepository::class, $this->getTestClassInstance());

        $this->assertSame($this->getTestClassInstance(), $this->getTestClassInstance());
    }

    /**
     * @test
     * @group user
     */
    public function is_must_return_model_method_of_bind_model_class(): void
    {
        $this->assertEquals(HCUser::class, $this->getTestClassInstance()->model());
    }

    /**
     * @test
     * @group user
     */
    public function it_must_return_user_by_id(): void
    {
        // create user via factory
        $expected = factory(HCUser::class)->create();

        // execute function getById
        $user = $this->getTestClassInstance()->getById($expected->id);

        // assert created user id is equal to returned from getById response
        $this->assertEquals($expected->id, $user->id);
    }

    /**
     * @test
     * @group user
     */
    public function it_should_get_all_selected_columns(): void
    {
        $count = mt_rand(2, 10);

        $i = 0;
        /** @var Collection|HCUser[] $users */
        $users = factory(HCUser::class, $count)->create();

        $testRepository = $this->getTestClassInstance();

        $this->assertCount($count, $testRepository->all());
        $testRepository->all()->each(function (HCUser $user) use ($users, &$i) {
            /** @var HCUser $factoryUser */
            $factoryUser = $users->get($i++);

            foreach (array_keys($user->getAttributes()) as $attribute) {
                if ($attribute !== 'count') {
                    $this->assertEquals($factoryUser->$attribute, $user->$attribute);
                }
            }
        });

        $i = 0;
        $testRepository->all(['email'])->each(function (HCUser $user) use ($users, &$i) {
            /** @var HCUser $factoryUser */
            $factoryUser = $users->get($i++);
            $this->assertEquals($factoryUser->email, $user->email);
            $this->assertEquals(['email'], array_keys($user->getAttributes()));
        });
    }

    /**
     * @return HCUserRepository
     */
    private function getTestClassInstance(): HCUserRepository
    {
        return $this->app->make(HCUserRepository::class);
    }
}
