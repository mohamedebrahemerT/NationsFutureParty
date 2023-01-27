<?php

use App\Models\CompanyAddress;
use App\Models\Invoice;
use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyAddressesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_addresses', function (Blueprint $table) {
            $table->id();
            $table->mediumText('address');
            $table->boolean('is_default');
            $table->timestamps();
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->bigInteger('company_address_id')->unsigned()->nullable();
            $table->foreign('company_address_id')->references('id')->on('company_addresses')->onDelete('SET NULL')->onUpdate('cascade');
        });

        $setting = Setting::first();

        if (!is_null($setting)) {
            $address = CompanyAddress::create([
                'address' => $setting->address,
                'is_default' => 1
            ]);

            Invoice::whereNull('company_address_id')->update(['company_address_id' => $address->id]);
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['company_address_id']);
            $table->dropColumn(['company_address_id']);
        });

        Schema::dropIfExists('company_addresses');
    }

}
