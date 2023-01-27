<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMailConnectionColumnEmailSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::table('smtp_settings', function (Blueprint $table) {
            $table->enum('mail_connection', ['sync', 'database'])->default('sync');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('smtp_settings', function (Blueprint $table) {
            $table->dropColumn(['mail_connection']);
        });
    }

}
