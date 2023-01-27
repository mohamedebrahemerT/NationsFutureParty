<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPayfastToPaymentGatewayCredentials extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_gateway_credentials', function (Blueprint $table) {
            $table->string('payfast_merchant_id')->nullable();
            $table->string('payfast_merchant_key')->nullable();
            $table->string('payfast_passphrase')->nullable();
            $table->enum('payfast_mode', ['sandbox', 'live'])->default('sandbox');
            $table->enum('payfast_status', ['active', 'deactive'])->default('deactive')->nullable();
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
            $table->dropColumn('payfast_merchant_id');
            $table->dropColumn('payfast_merchant_key');
            $table->dropColumn('payfast_passphrase');
            $table->dropColumn('payfast_mode');
            $table->dropColumn('payfast_status');
        });
    }

}
