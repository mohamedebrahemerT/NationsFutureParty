<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecurringInvoiceItemImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('invoice_recurring_item_images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('invoice_recurring_item_id')->unsigned();
            $table->foreign('invoice_recurring_item_id')->references('id')->on('invoice_recurring_items')->onDelete('cascade')->onUpdate('cascade');
            $table->string('filename');
            $table->string('hashname')->nullable();
            $table->string('size')->nullable();
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
        Schema::dropIfExists('invoice_recurring_item_images');
    }

}
