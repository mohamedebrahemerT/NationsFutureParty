<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAuthorizeToPaymentGatewayCredentials extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_gateway_credentials', function (Blueprint $table) {
            $table->string('authorize_api_login_id')->nullable();
            $table->string('authorize_transaction_key')->nullable();
            $table->enum('authorize_environment', ['sandbox', 'live'])->default('sandbox');
            $table->enum('authorize_status', ['active', 'deactive'])->default('deactive');

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
            $table->dropColumn('authorize_api_login_id');
            $table->dropColumn('authorize_transaction_key');
            $table->dropColumn('authorize_environment');
            $table->dropColumn('authorize_status');
        });
    }

}
