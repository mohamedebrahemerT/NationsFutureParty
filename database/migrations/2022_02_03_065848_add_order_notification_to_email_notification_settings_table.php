<?php

use App\Models\EmailNotificationSetting;
use App\Models\Permission;
use Illuminate\Database\Migrations\Migration;

class AddOrderNotificationToEmailNotificationSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        $setting = new EmailNotificationSetting();
        $setting->setting_name = 'Order Create/Update Notification';
        $setting->send_email = 'no';
        $setting->send_slack = 'no';
        $setting->send_push = 'no';
        $setting->slug = str_slug($setting->setting_name);
        $setting->save();

        Permission::where('name', 'add_project_files')->update(['allowed_permissions' => '{"all":4, "none":5}']);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        EmailNotificationSetting::where('slug', 'order-createupdate-notification')->delete();
    }

}
