<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHcUserProvider extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('hc_user_provider', function (Blueprint $table) {
            $table->increments('count');
            $table->uuid('id')->unique();
            $table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->datetime('deleted_at')->nullable();

            $table->string('user_id', 36);
            $table->string('user_provider_id')->nullable();
            $table->enum('provider', ['facebook', 'twitter', 'linkedin', 'google', 'github', 'bitbucket']);
            $table->string('profile_url')->nullable();
            $table->string('email')->nullable();
            $table->text('response')->nullable();

            $table->foreign('user_id')->references('id')->on('hc_user')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('hc_user_provider');
    }
}
