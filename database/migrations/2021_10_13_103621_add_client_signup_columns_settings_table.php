<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClientSignupColumnsSettingsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organisation_settings', function (Blueprint $table) {
            $table->boolean('allow_client_signup');
            $table->boolean('admin_client_signup_approval');
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
            $table->dropColumn(['allow_client_signup']);
            $table->dropColumn(['admin_client_signup_approval']);
        });
    }

}
