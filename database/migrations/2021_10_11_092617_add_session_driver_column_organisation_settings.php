<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSessionDriverColumnOrganisationSettings extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $defaultDriver = env('SESSION_DRIVER');

        if ($defaultDriver != 'database') {
            $defaultDriver = 'file';
        }

        Schema::table('organisation_settings', function (Blueprint $table) use ($defaultDriver) {
            $table->enum('session_driver', ['file', 'database'])->default($defaultDriver);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organisation_settings', function (Blueprint $table) {
            $table->dropColumn(['session_driver']);
        });
    }

}
