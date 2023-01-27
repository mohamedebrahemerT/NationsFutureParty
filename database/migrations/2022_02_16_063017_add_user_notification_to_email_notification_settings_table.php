<?php

use App\Models\EmailNotificationSetting;
use App\Models\Permission;
use Illuminate\Database\Migrations\Migration;

class AddUserNotificationToEmailNotificationSettingsTable extends Migration
{

    public function up()
    {
        $setting = new EmailNotificationSetting();
        $setting->setting_name = 'User Join via Invitation';
        $setting->send_email = 'no';
        $setting->send_slack = 'no';
        $setting->send_push = 'no';
        $setting->slug = str_slug($setting->setting_name);
        $setting->save();

        Permission::where('name', 'user-join-via-invitation')->update(['allowed_permissions' => '{"all":4, "none":5}']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        EmailNotificationSetting::where('slug', 'user-join-via-invitation')->delete();
    }

}
