<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGoogleRecaptchaColumnsInOrganisationSettingsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organisation_settings', function (Blueprint $table) {

            $table->enum('google_recaptcha_status', ['active', 'deactive'])->default('deactive');

            $table->enum('google_recaptcha_v2_status', ['active', 'deactive'])->default('deactive');
            $table->string('google_recaptcha_v2_site_key')->nullable();
            $table->string('google_recaptcha_v2_secret_key')->nullable();

            $table->enum('google_recaptcha_v3_status', ['active', 'deactive'])->default('deactive');
            $table->string('google_recaptcha_v3_site_key')->nullable();
            $table->string('google_recaptcha_v3_secret_key')->nullable();

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
            $table->removeColumn('google_recaptcha_status');
            $table->removeColumn('google_recaptcha_v2_status');
            $table->removeColumn('google_recaptcha_v2_site_key');
            $table->removeColumn('google_recaptcha_v2_secret_key');
            $table->removeColumn('google_recaptcha_v3_status');
            $table->removeColumn('google_recaptcha_v3_site_key');
            $table->removeColumn('google_recaptcha_v3_secret_key');
        });
    }

}
