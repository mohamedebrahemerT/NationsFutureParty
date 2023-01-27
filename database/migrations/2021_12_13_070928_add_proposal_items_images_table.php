<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProposalItemsImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('proposal_item_images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('proposal_item_id')->unsigned();
            $table->foreign('proposal_item_id')->references('id')->on('proposal_items')->onDelete('cascade')->onUpdate('cascade');
            $table->string('filename');
            $table->string('hashname')->nullable();
            $table->string('size')->nullable();
            $table->string('external_link')->nullable();
            $table->timestamps();
        });

        Schema::create('estimate_item_images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('estimate_item_id')->unsigned();
            $table->foreign('estimate_item_id')->references('id')->on('estimate_items')->onDelete('cascade')->onUpdate('cascade');
            $table->string('filename');
            $table->string('hashname')->nullable();
            $table->string('size')->nullable();
            $table->string('external_link')->nullable();
            $table->timestamps();
        });

        Schema::create('credit_note_item_images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('credit_note_item_id')->unsigned();
            $table->foreign('credit_note_item_id')->references('id')->on('credit_note_items')->onDelete('cascade')->onUpdate('cascade');
            $table->string('filename')->nullable();
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
        Schema::dropIfExists('proposal_item_images');
        Schema::dropIfExists('estimate_item_images');
        Schema::dropIfExists('credit_note_item_images');
    }

}
