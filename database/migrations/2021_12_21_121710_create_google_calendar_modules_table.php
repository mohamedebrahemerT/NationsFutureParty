<?php

use App\Models\GoogleCalendarModule;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoogleCalendarModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('google_calendar_modules', function (Blueprint $table) {
            $table->id();
            $table->boolean('lead_status')->default(0);
            $table->boolean('leave_status')->default(0);
            $table->boolean('invoice_status')->default(0);
            $table->boolean('contract_status')->default(0);
            $table->boolean('task_status')->default(0);
            $table->boolean('event_status')->default(0);
            $table->boolean('holiday_status')->default(0);
            $table->timestamps();
        });

        /* Add default entry */
        $module = new GoogleCalendarModule();
        $module->lead_status = 0;
        $module->leave_status = 0;
        $module->invoice_status = 0;
        $module->contract_status = 0;
        $module->task_status = 0;
        $module->event_status = 0;
        $module->holiday_status = 0;
        $module->save();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('google_calendar_modules');
    }

}
