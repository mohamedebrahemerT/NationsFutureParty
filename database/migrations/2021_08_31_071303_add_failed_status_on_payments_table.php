<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFailedStatusOnPaymentsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE payments CHANGE COLUMN status status ENUM('complete', 'pending', 'failed') NOT NULL DEFAULT 'pending'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE payments CHANGE COLUMN status status ENUM('complete', 'pending') NOT NULL DEFAULT 'pending'");
    }

}
