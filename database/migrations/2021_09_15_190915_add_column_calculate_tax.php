<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnCalculateTax extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->enum('calculate_tax', ['after_discount', 'before_discount'])->default('after_discount');
        });

        Schema::table('estimates', function (Blueprint $table) {
            $table->enum('calculate_tax', ['after_discount', 'before_discount'])->default('after_discount');
        });

        Schema::table('proposals', function (Blueprint $table) {
            $table->enum('calculate_tax', ['after_discount', 'before_discount'])->default('after_discount');
        });

        Schema::table('credit_notes', function (Blueprint $table) {
            $table->enum('calculate_tax', ['after_discount', 'before_discount'])->default('after_discount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['calculate_tax']);
        });

        Schema::table('estimates', function (Blueprint $table) {
            $table->dropColumn(['calculate_tax']);
        });

        Schema::table('proposals', function (Blueprint $table) {
            $table->dropColumn(['calculate_tax']);
        });

        Schema::table('credit_notes', function (Blueprint $table) {
            $table->dropColumn(['calculate_tax']);
        });
    }

}
