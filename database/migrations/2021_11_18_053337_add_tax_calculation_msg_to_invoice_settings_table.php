<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTaxCalculationMsgToInvoiceSettingsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_settings', function (Blueprint $table) {
            $table->boolean('tax_calculation_msg')->default(0);
        });

        Schema::table('invoice_recurring', function (Blueprint $table) {
            $table->enum('calculate_tax', ['after_discount', 'before_discount'])->default('after_discount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_settings', function (Blueprint $table) {
            $table->dropColumn(['tax_calculation_msg']);
        });

        Schema::table('invoice_recurring', function (Blueprint $table) {
            $table->dropColumn(['calculate_tax']);
        });
    }

}
