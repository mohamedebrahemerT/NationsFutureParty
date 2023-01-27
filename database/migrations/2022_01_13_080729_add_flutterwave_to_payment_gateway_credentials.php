<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFlutterwaveToPaymentGatewayCredentials extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_gateway_credentials', function (Blueprint $table) {
            $table->enum('flutterwave_status', ['active', 'deactive'])->default('deactive');
            $table->enum('flutterwave_mode', ['sandbox', 'live'])->default('sandbox');
            $table->string('test_flutterwave_key')->nullable();
            $table->string('test_flutterwave_secret')->nullable();
            $table->string('test_flutterwave_hash')->nullable();
            $table->string('live_flutterwave_key')->nullable();
            $table->string('live_flutterwave_secret')->nullable();
            $table->string('live_flutterwave_hash')->nullable();
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
            $table->dropColumn('flutterwave_status');
            $table->dropColumn('flutterwave_mode');
            $table->dropColumn('test_flutterwave_key');
            $table->dropColumn('test_flutterwave_secret');
            $table->dropColumn('test_flutterwave_hash');
            $table->dropColumn('live_flutterwave_key');
            $table->dropColumn('live_flutterwave_secret');
            $table->dropColumn('live_flutterwave_hash');
        });
    }

}
