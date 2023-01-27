<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderItemImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('order_item_images', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('order_item_id')->unsigned()->index()->nullable();
            $table->foreign('order_item_id')->references('id')->on('order_items')->onDelete('cascade');
            $table->string('external_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_item_images');
    }

}
