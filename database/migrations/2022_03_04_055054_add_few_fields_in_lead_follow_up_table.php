<?php

use App\Models\EmailNotificationSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFewFieldsInLeadFollowUpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::table('lead_follow_up', function (Blueprint $table) {
            $table->enum('send_reminder', ['yes', 'no'])->nullable()->default('no');
            $table->text('remind_time')->nullable();
            $table->enum('remind_type', ['minute', 'hour', 'day'])->nullable();
        });

        $setting = new EmailNotificationSetting();
        $setting->setting_name = 'Follow Up Reminder';
        $setting->send_email = 'no';
        $setting->send_slack = 'no';
        $setting->send_push = 'no';
        $setting->slug = str_slug($setting->setting_name);
        $setting->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lead_follow_up', function (Blueprint $table) {
            $table->dropColumn(['send_reminder', 'remind_time', 'remind_type']);
        });

        EmailNotificationSetting::where('slug', 'follow-up-reminder')->delete();
    }
    
}
