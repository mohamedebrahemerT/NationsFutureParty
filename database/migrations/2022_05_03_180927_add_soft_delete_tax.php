<?php

use App\Models\Invoice;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeleteTax extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('taxes', function (Blueprint $table) {
            $table->softDeletes();
        });

        $defaultAddress = \App\Models\CompanyAddress::defaultAddress();

        if (!is_null($defaultAddress)) {
            Invoice::whereNull('company_address_id')->update(['company_address_id' => $defaultAddress->id]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('taxes', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }

}
