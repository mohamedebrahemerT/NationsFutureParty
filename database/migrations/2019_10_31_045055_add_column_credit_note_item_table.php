<?php

use App\Models\CreditNoteItem;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnCreditNoteItemTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('credit_note_items', function (Blueprint $table) {
            $table->string('taxes')->nullable()->default(null)->after('amount');
        });

        Schema::table('credit_note_items', function (Blueprint $table) {
            $table->dropForeign('credit_note_items_tax_id_foreign');
            $table->dropColumn('tax_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('credit_note_items', function (Blueprint $table) {
            $table->dropColumn('taxes');
        });
    }

}
