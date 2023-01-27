<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\TicketEmailSetting;

class CreateTicketEmailSettingsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_email_settings', function (Blueprint $table) {
            $table->id();
            $table->string('mail_username')->nullable();
            $table->string('mail_password')->nullable();
            $table->string('mail_from_name')->nullable();
            $table->string('mail_from_email')->nullable();

            $table->string('imap_host')->nullable();
            $table->string('imap_port')->nullable();
            $table->string('imap_encryption')->nullable();

            $table->boolean('status');
            $table->boolean('verified');
            $table->integer('sync_interval')->default(1);
            $table->timestamps();
        });

        $setting = new TicketEmailSetting();
        $setting->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticket_email_settings');
    }

}
