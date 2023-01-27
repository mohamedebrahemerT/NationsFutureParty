<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCurrencyKeyVersionColumn extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organisation_settings', function (Blueprint $table) {
            $table->enum('currency_key_version', ['free', 'api'])->default('free');
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
            $table->dropColumn(['currency_key_version']);
        });
    }

}
