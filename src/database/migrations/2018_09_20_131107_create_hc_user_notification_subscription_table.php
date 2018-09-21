<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHcUserNotificationSubscriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hc_user_notification_subscription', function (Blueprint $table) {
            $table->increments('count');
            $table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->uuid('user_id');
            $table->string('type_id');

            $table->unique(['user_id', 'type_id']);

            $table->foreign('user_id')->references('id')->on('hc_user')
                ->onUpdate('CASCADE')->onDelete('CASCADE');

            $table->foreign('type_id')->references('id')->on('hc_user_notification_subscription_type')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hc_user_notification');
    }
}
