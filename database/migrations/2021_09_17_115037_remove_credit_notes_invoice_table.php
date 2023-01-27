<?php

use Illuminate\Database\Migrations\Migration;

class RemoveCreditNotesInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::drop('credit_notes_invoice');
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
