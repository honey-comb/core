<?php

declare(strict_types = 1);

namespace Tests\Feature\Repositories;


use Faker\Generator;
use Illuminate\Support\Collection;
use HoneyComb\Core\Models\HCUuidModel;
use HoneyComb\Core\Repositories\HCBaseRepository;
use Tests\FeatureTestCase;

/**
 * Class RepositoryTest
 * @package Tests\Feature\Repositories
 */
class RepositoryTest extends FeatureTestCase
{
    /**
     *
     */
    protected function setUp()
    {
        parent::setUp();

        $this->factory->define(HCUser::class, function (Generator $faker) {
            static $password;

            return [
                'email' => $faker->email,
                'password' => $password ?: $password = bcrypt('secret'),
                'remember_token' => str_random(10),
                'last_visited' => $faker->dateTime,
            ];
        });
    }

    /**
     * @test
     */
    public function is_should_return_model_method_of_bind_model_class(): void
    {
        $this->assertEquals(HCUser::class, $this->getTestClassInstance()->model());
    }

    /**
     * @test
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
     * @return RepositoryFake
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function getTestClassInstance(): RepositoryFake
    {
        return $this->app->make(RepositoryFake::class);
    }
}

/**
 * Class HCUser
 * @package Tests\Feature\Repositories
 */
class HCUser extends HCUuidModel
{
    /**
     * @var string
     */
    protected $table = 'hc_users';
    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'activated_at',
        'last_login',
        'last_visited',
        'last_activity',
        'email',
        'password',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'activated_at' => 'datetime',
        'last_login' => 'datetime',
        'last_visited' => 'datetime',
        'last_activity' => 'datetime',
    ];
}

/**
 * Class RepositoryFake
 * @package Tests\Feature\Repositories
 */
class RepositoryFake extends HCBaseRepository
{
    /**
     * @return string
     */
    public function model(): string
    {
        return HCUser::class;
    }
}
