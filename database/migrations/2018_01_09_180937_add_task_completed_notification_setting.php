<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTaskCompletedNotificationSetting extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\Models\EmailNotificationSetting::create([
            'setting_name' => 'Task Completed',
            'send_email' => 'yes'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\Models\EmailNotificationSetting::where('setting_name', 'Task Completed')->delete();
    }

}
