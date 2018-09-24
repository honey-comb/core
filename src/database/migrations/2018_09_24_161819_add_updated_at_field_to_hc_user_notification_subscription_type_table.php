<?php

declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddUpdatedAtFieldToHcUserNotificationSubscriptionTypeTable
 */
class AddUpdatedAtFieldToHcUserNotificationSubscriptionTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('hc_user_notification_subscription_type', function (Blueprint $table) {
            $table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('hc_user_notification_subscription_type', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
    }
}
