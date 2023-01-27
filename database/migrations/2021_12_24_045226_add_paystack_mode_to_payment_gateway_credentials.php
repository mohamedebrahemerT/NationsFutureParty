<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaystackModeToPaymentGatewayCredentials extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_gateway_credentials', function (Blueprint $table) {
            $table->enum('paystack_mode', ['sandbox', 'live'])->default('sandbox')->after('paystack_status');
            $table->string('test_paystack_key')->nullable()->after('paystack_mode');
            $table->string('test_paystack_secret')->nullable()->after('test_paystack_key');
            $table->string('test_paystack_merchant_email')->nullable()->after('test_paystack_secret');
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
            $table->dropColumn('paystack_mode');
            $table->dropColumn('test_paystack_key');
            $table->dropColumn('test_paystack_secret');
            $table->dropColumn('test_paystack_merchant_email');
        });
    }

}
