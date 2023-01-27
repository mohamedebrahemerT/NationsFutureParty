<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGoogleEventIdToSeveralTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::table('lead_follow_up', function (Blueprint $table) {
            $table->text('event_id')->nullable();
        });

        Schema::table('leaves', function (Blueprint $table) {
            $table->text('event_id')->nullable();
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->text('event_id')->nullable();
        });

        Schema::table('contracts', function (Blueprint $table) {
            $table->text('event_id')->nullable();
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->text('event_id')->nullable();
        });

        Schema::table('events', function (Blueprint $table) {
            $table->text('event_id')->nullable();
        });

        Schema::table('holidays', function (Blueprint $table) {
            $table->text('event_id')->nullable();
        });

        Schema::table('organisation_settings', function (Blueprint $table) {
            $table->enum('google_calendar_status', ['active', 'inactive'])->default('inactive');
            $table->text('google_client_id')->nullable();
            $table->text('google_client_secret')->nullable();
            $table->enum('google_calendar_verification_status', ['verified', 'non_verified'])->default('non_verified');
            $table->string('google_id')->nullable();
            $table->string('name')->nullable();
            $table->text('token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropColumn('event_id');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('event_id');
        });

        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn('event_id');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('event_id');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('event_id');
        });

        Schema::table('holidays', function (Blueprint $table) {
            $table->dropColumn('event_id');
        });

    }

}
