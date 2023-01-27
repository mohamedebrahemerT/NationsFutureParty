<?php

use App\Models\EmailNotificationSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewForNullSlugEmailNotificationTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_notification_settings', function (Blueprint $table) {
            $settings = EmailNotificationSetting::whereNull('slug')->get();
            
            if($settings)
            {
                foreach ($settings as $setting) {
                    $setting->slug = str_slug($setting->setting_name);
                    $setting->save();
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('email_notification_settings', function (Blueprint $table) {
            
        });
    }

}
