<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Add2faColumnsOnUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('users', function(Blueprint $table) {
            $table->enum('two_fa_status', ['active', 'inactive'])->default('inactive');
            $table->enum('two_fa_verify_via', ['email', 'google_authenticator'])->default('email');
            $table->string('two_factor_code')->nullable()->comment('when authenticator is email');
            $table->dateTime('two_factor_expires_at')->nullable();
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
            $table->dropColumn(['two_fa_status', 'two_fa_verify_via', 'two_factor_code', 'two_factor_expires_at']);
        });
    }

}
