<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUseridOnProjectTemplateMember extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_template_members', function (Blueprint $table) {
            $table->dropForeign('project_template_members_user_id_foreign');
            $table->dropIndex('project_template_members_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_template_members', function (Blueprint $table) {
            $table->dropForeign('project_template_members_user_id_foreign');
            $table->dropIndex('project_template_members_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

}
