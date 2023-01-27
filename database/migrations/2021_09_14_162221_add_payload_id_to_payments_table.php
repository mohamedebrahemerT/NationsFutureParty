<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPayloadIdToPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('payload_id')->nullable();

            $table->integer('credit_notes_id')->unsigned()->after('order_id')->nullable();
            $table->foreign('credit_notes_id')->references('id')->on('credit_notes')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('due_amount');
        });

        Schema::table('credit_notes', function (Blueprint $table) {
            $table->float('adjustment_amount')->nullable()->after('total');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['credit_notes_id']);
            $table->dropColumn(['payload_id', 'credit_notes_id']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('due_amount')->nullable();
        });

        Schema::table('credit_notes', function (Blueprint $table) {
            $table->dropColumn('adjustment_amount');
        });
    }

}
