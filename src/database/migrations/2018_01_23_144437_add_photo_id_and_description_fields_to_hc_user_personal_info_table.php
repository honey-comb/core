<?php
declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhotoIdAndDescriptionFieldsToHcUserPersonalInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('hc_user_personal_info', function (Blueprint $table) {
            $table->uuid('photo_id')->nullable();
            $table->text('description')->nullable();

            $table->foreign('photo_id')->references('id')->on('hc_resource')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
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
            $table->dropForeign(['photo_id']);

            $table->dropColumn(['photo_id', 'description']);
        });
    }
}
