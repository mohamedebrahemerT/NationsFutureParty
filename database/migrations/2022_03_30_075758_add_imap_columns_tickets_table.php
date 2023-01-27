<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImapColumnsTicketsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket_replies', function (Blueprint $table) {
            $table->string('imap_message_id')->nullable();
            $table->string('imap_message_uid')->nullable();
            $table->string('imap_in_reply_to')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticket_replies', function (Blueprint $table) {
            $table->dropColumn(['imap_message_id']);
            $table->dropColumn(['imap_message_uid']);
            $table->dropColumn(['imap_in_reply_to']);
        });
    }

}
