<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHcUserProviders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('hc_user_providers', function (Blueprint $table) {
            $table->increments('count');
            $table->string('id', 36)->unique();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->softDeletes();

            $table->string('user_id', 36);
            $table->string('user_provider_id')->nullable();
            $table->enum('provider', ['facebook', 'twitter', 'linkedin', 'google', 'github', 'bitbucket']);
            $table->text('response')->nullable();

            $table->foreign('user_id')->references('id')->on('hc_users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('hc_user_providers');
    }
}
