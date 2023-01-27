<?php

use App\Models\CompanyAddress;
use App\Models\InvoiceSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MoveGstToCompaneAddress extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_addresses', function (Blueprint $table) {
            $table->string('tax_number')->nullable();
            $table->string('tax_name')->nullable();
        });

        $invoiceSetting = InvoiceSetting::first();
        $defaultBusinessAddress = CompanyAddress::where('is_default', 1)->first();

        if ($defaultBusinessAddress) {
            $defaultBusinessAddress->tax_number = $invoiceSetting->gst_number;
            $defaultBusinessAddress->tax_name = 'GST IN';
            $defaultBusinessAddress->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_addresses', function (Blueprint $table) {
            $table->dropColumn(['tax_number']);
            $table->dropColumn(['tax_name']);
        });
    }

}
