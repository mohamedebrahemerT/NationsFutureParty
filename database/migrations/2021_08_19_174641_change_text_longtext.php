<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTextLongtext extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('discussion_replies', function (Blueprint $table) {
            $table->longText('body')->change();
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->longText('project_summary')->change();
        });

        Schema::table('users_chat', function (Blueprint $table) {
            $table->longText('message')->change();
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->longText('description')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

}
