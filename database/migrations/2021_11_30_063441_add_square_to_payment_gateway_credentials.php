<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSquareToPaymentGatewayCredentials extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_gateway_credentials', function (Blueprint $table) {
            $table->string('square_application_id')->nullable();
            $table->string('square_access_token')->nullable();
            $table->string('square_location_id')->nullable();
            $table->enum('square_environment', ['sandbox', 'production'])->default('sandbox');
            $table->enum('square_status', ['active', 'deactive'])->default('deactive');
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
            $table->dropColumn('square_application_id');
            $table->dropColumn('square_access_token');
            $table->dropColumn('square_location_id');
            $table->dropColumn('square_environment');
            $table->dropColumn('square_status');
        });
    }

}
