<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetDiscussionCategoryIdNullOnCategoryDelete extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::table('discussions', function (Blueprint $table) {
            $table->dropForeign(['discussion_category_id']);
            /** @phpstan-ignore-next-line */
            $table->integer('discussion_category_id')->unsigned()->nullable()->change();
            /** @phpstan-ignore-next-line */
            $table->foreign('discussion_category_id')->references('id')->on('discussion_categories')->onDelete('set null')->onUpdate('cascade')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('discussions', function (Blueprint $table) {
            $table->dropForeign(['discussion_category_id']);
            $table->foreign('discussion_category_id')->references('id')->on('discussion_categories')->onDelete('cascade')->onUpdate('cascade');
        });
    }

}
