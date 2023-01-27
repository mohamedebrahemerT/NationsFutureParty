<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddedByColumnKnowledgeBaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::table('knowledge_bases', function (Blueprint $table) {
            $table->integer('added_by')->unsigned()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('knowledge_bases', function (Blueprint $table) {
            $table->dropColumn('added_by');
        });
    }

}
