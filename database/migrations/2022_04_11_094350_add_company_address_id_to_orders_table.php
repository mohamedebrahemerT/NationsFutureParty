<?php

use App\Models\Order;
use App\Models\CompanyAddress;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyAddressIdToOrdersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->bigInteger('company_address_id')->unsigned()->nullable();
            $table->foreign('company_address_id')->references('id')->on('company_addresses')->onDelete('SET NULL')->onUpdate('cascade');
        });

        $companyAddress = CompanyAddress::first();

        if($companyAddress) {
            Order::whereNull('company_address_id')->update(['company_address_id' => $companyAddress->id]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['company_address_id']);
            $table->dropColumn(['company_address_id']);
        });
    }

}
