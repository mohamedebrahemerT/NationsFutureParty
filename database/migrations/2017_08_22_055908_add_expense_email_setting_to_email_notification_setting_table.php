<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExpenseEmailSettingToEmailNotificationSettingTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // When new expense added by admin
        \App\Models\EmailNotificationSetting::create([
            'setting_name' => 'New Expense/Added by Admin',
            'send_email' => 'yes'
        ]);

        // When new expense added by member
        \App\Models\EmailNotificationSetting::create([
            'setting_name' => 'New Expense/Added by Member',
            'send_email' => 'yes'
        ]);

        // When expense status changed
        \App\Models\EmailNotificationSetting::create([
            'setting_name' => 'Expense Status Changed',
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
        \App\Models\EmailNotificationSetting::where('setting_name', 'New Expense/Added by Admin')->delete();
        \App\Models\EmailNotificationSetting::where('setting_name', 'New Expense/Added by Member')->delete();
        \App\Models\EmailNotificationSetting::where('setting_name', 'Expense Status Changed')->delete();
    }

}
