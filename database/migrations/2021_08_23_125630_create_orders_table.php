<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->integer('client_id')->unsigned()->nullable();
            $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->date('order_date');
            $table->float('sub_total');
            $table->float('total');
            $table->float('due_amount')->default(0);
            $table->enum('status', ['paid', 'unpaid'])->default('unpaid');

            $table->integer('currency_id')->unsigned()->nullable();
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade')->onUpdate('cascade');

            $table->enum('show_shipping_address', ['yes', 'no'])->default('no');

            $table->string('note')->nullable();

            $table->integer('added_by')->unsigned()->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');

            $table->integer('last_updated_by')->unsigned()->nullable();
            $table->foreign('last_updated_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');

            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade')->onUpdate('cascade');

            $table->string('item_name');
            $table->text('item_summary')->nullable();
            $table->enum('type', ['item', 'discount', 'tax'])->default('item');
            $table->integer('quantity');
            $table->integer('unit_price');
            $table->float('amount');
            $table->string('hsn_sac_code')->nullable();
            $table->string('taxes')->nullable()->default(null);
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
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }

}
