<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class ChangeAuthenticateViaColumn extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN two_fa_verify_via ENUM('email', 'google_authenticator', 'both') NULL");

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('two_fa_status');
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
            $table->enum('two_fa_status', ['active', 'inactive'])->default('inactive');
        });

    }

}
