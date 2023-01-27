<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveOnDeleteCascadeFromCategoryIdOnProductsTable extends Migration
{

    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->foreign('category_id')->references('id')->on('product_category')->onDelete('set null')->onUpdate('cascade');

            $table->dropForeign(['sub_category_id']);
            $table->foreign('sub_category_id')->references('id')->on('product_category')->onDelete('set null')->onUpdate('cascade');
        });
    }

            /**
             * Reverse the migrations.
             *
             * @return void
             */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['sub_category_id']);
            $table->foreign('sub_category_id')->references('id')->on('product_category')->onDelete('cascade')->onUpdate('cascade');

            $table->dropForeign(['category_id']);
            $table->foreign('category_id')->references('id')->on('product_category')->onDelete('cascade')->onUpdate('cascade');
        });
    }

}
