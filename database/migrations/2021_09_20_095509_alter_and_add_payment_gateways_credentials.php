<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterAndAddPaymentGatewaysCredentials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::table('payment_gateway_credentials', function (Blueprint $table) {
            $table->string('test_stripe_client_id')->nullable();
            $table->string('test_stripe_secret')->nullable();
            $table->string('test_razorpay_key')->nullable();
            $table->string('test_razorpay_secret')->nullable();
            $table->string('test_stripe_webhook_secret')->nullable();
            $table->enum('stripe_mode', ['test', 'live'])->default('test');
            $table->enum('razorpay_mode', ['test', 'live'])->default('test');

            /* Rename old column names */
            $table->renameColumn('stripe_client_id', 'live_stripe_client_id');
            $table->renameColumn('stripe_secret', 'live_stripe_secret');
            $table->renameColumn('razorpay_key', 'live_razorpay_key');
            $table->renameColumn('razorpay_secret', 'live_razorpay_secret');
            $table->renameColumn('stripe_webhook_secret', 'live_stripe_webhook_secret');
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
            $table->dropColumn('test_stripe_client_id');
            $table->dropColumn('test_stripe_secret');
            $table->dropColumn('test_razorpay_key');
            $table->dropColumn('test_razorpay_secret');
            $table->dropColumn('test_stripe_webhook_secret');
            $table->dropColumn('stripe_mode');
            $table->dropColumn('razorpay_mode');

            /* Rename old column names */
            $table->renameColumn('live_stripe_client_id', 'stripe_client_id');
            $table->renameColumn('live_stripe_secret', 'stripe_secret');
            $table->renameColumn('live_razorpay_key', 'razorpay_key');
            $table->renameColumn('live_razorpay_secret', 'razorpay_secret');
            $table->renameColumn('live_stripe_webhook_secret', 'stripe_webhook_secret');
        });
    }

}
