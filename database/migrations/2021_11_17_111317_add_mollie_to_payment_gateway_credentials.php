<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMollieToPaymentGatewayCredentials extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_gateway_credentials', function (Blueprint $table) {
            $table->string('mollie_api_key')->nullable();
            $table->enum('mollie_status', ['active', 'deactive'])->default('deactive')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_gateway_credentials', function (Blueprint $table) {
            $table->dropColumn('mollie_api_key');
            $table->dropColumn('mollie_status');
        });
    }

}
