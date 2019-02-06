<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddPhotoFieldToHcUserPersonalInfoTable
 */
class AddPhotoFieldToHcUserPersonalInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('hc_user_personal_info', function (Blueprint $table) {
            $table->string('photo')->nullable()->after('photo_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('hc_user_personal_info', function (Blueprint $table) {
            $table->dropColumn('photo');
        });
    }
}
