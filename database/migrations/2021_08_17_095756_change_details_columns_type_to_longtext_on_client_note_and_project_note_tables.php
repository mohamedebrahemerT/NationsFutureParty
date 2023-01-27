<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDetailsColumnsTypeToLongtextOnClientNoteAndProjectNoteTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::table('client_notes', function (Blueprint $table) {
            $table->longText('details')->change();
        });
        Schema::table('project_notes', function (Blueprint $table) {
            $table->longText('details')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }

}
