<?php

use App\Models\Invoice;
use Illuminate\Database\Migrations\Migration;

class AddMissingHashInInvoicesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Invoice::whereNull('hash')->update(['hash' => \Illuminate\Support\Str::random(32)]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }

}
