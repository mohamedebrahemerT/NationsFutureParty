<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocationColumnCompanyAddresses extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_addresses', function (Blueprint $table) {
            $table->string('location')->nullable();
        });

        $setting = \App\Models\Setting::first();
        $address = \App\Models\CompanyAddress::first();

        if($setting){
            $address->location = $setting->company_name;
            $address->save();
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
            $table->dropColumn(['location']);
        });
    }

}
