<?php

declare(strict_types = 1);

namespace Tests;


use Illuminate\Database\Eloquent\Factory;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class FeatureTestCase
 * @package Tests
 */
abstract class FeatureTestCase extends TestCase
{
    /**
     * @var  Factory
     */
    protected $factory;

    /**
     *
     */
    protected function setUp()
    {
        parent::setUp();

        $this->factory = $this->app->make(Factory::class);

        $this->migrate();
    }

    /**
     *
     */
    protected function tearDown()
    {
        $this->dropTables();

        parent::tearDown();
    }

    /**
     * Created tables for testing
     */
    private function migrate(): void
    {
        Schema::create('hc_acl_role', function(Blueprint $table) {
            $table->increments('count');
            $table->string('id')->unique();
            $table->timestamps();
            $table->softDeletes();
            $table->string('name', 100)->unique();
            $table->string('slug', 100)->unique();
        });

        Schema::create('hc_acl_role_user_connection', function(Blueprint $table) {
            $table->increments('count');
            $table->timestamps();
            $table->string('role_id', 36);
            $table->string('user_id', 36);
        });

        Schema::create('hc_user', function(Blueprint $table) {
            $table->increments('count');
            $table->string('id', 36)->unique();
            $table->timestamps();
            $table->softDeletes();
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamp('activated_at')->nullable();
            $table->rememberToken();
            $table->timestamp('last_login')->nullable();
            $table->timestamp('last_visited')->nulable();
            $table->timestamp('last_activity')->nullable();
        });
    }

    /**
     * Drop created tables
     */
    private function dropTables(): void
    {
        Schema::drop('hc_user');
        Schema::drop('hc_acl_role_user_connection');
        Schema::drop('hc_acl_role');
    }
}