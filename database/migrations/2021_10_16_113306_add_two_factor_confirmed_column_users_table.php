<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTwoFactorConfirmedColumnUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('two_factor_confirmed')
                ->after('two_factor_recovery_codes')
                ->default(false);
            $table->boolean('two_factor_email_confirmed')
                ->after('two_factor_confirmed')
                ->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['two_factor_email_confirmed']);
            $table->dropColumn(['two_factor_confirmed']);
        });
    }

}
