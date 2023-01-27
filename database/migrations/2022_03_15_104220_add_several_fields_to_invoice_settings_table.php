<?php

use App\Models\InvoiceSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeveralFieldsToInvoiceSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::table('invoice_settings', function (Blueprint $table) {
            $table->enum('show_client_name', ['yes', 'no'])->nullable()->default('no');
            $table->enum('show_client_email', ['yes', 'no'])->nullable()->default('no');
            $table->enum('show_client_phone', ['yes', 'no'])->nullable()->default('no');
            $table->enum('show_client_company_address', ['yes', 'no'])->nullable()->default('no');
            $table->enum('show_client_company_name', ['yes', 'no'])->nullable()->default('no');
        });

        $setting = InvoiceSetting::first();
        $setting->show_client_name = 'yes';
        $setting->show_client_email = 'yes';
        $setting->show_client_phone = 'yes';
        $setting->show_client_company_name = 'yes';
        $setting->show_client_company_address = 'yes';
        $setting->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_settings', function (Blueprint $table) {
            $table->dropColumn(['show_client_name', 'show_client_email', 'show_client_phone', 'show_client_company_name', 'show_client_company_address']);
        });
    }

}
