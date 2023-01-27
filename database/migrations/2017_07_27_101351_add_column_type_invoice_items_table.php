<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Invoice;
use App\Models\InvoiceItems;

class AddColumnTypeInvoiceItemsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->enum('type', ['item', 'discount', 'tax'])->default('item')->after('item_name');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['discount', 'tax_percent']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        InvoiceItems::where('type', 'tax')->orWhere('type', 'discount')->delete();

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropColumn(['type']);
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->float('discount')->after('sub_total');
            $table->float('tax_percent')->nullable();
        });

    }

}
