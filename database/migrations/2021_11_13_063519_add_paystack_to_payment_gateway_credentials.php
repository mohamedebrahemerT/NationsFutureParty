<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaystackToPaymentGatewayCredentials extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_gateway_credentials', function (Blueprint $table) {
            $table->string('paystack_key')->nullable();
            $table->string('paystack_secret')->nullable();
            $table->string('paystack_merchant_email')->nullable();
            $table->enum('paystack_status', ['active', 'deactive'])->default('deactive')->nullable();

            $table->string('paystack_payment_url')->default('https://api.paystack.co')->nullable();
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
            $table->dropColumn('paystack_key');
            $table->dropColumn('paystack_secret');
            $table->dropColumn('paystack_status');
            $table->dropColumn('paystack_merchant_email');
            $table->dropColumn('paystack_payment_url');
        });
    }

}
