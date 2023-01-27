<?php

use Illuminate\Database\Migrations\Migration;

class ChangeQuantityTypeInOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        DB::statement('ALTER TABLE `order_items` CHANGE `quantity` `quantity` DOUBLE(16,2) NOT NULL');
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
