<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

class AlterDateFormatTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $organizationSettings = Setting::all();
        
        foreach ($organizationSettings as  $organizationSetting) {
            if($organizationSetting->date_format === 'D-M-Y'){
                $organizationSetting->date_format = 'D d M Y';
            }
            elseif($organizationSetting->date_format === 'D.M.Y'){
                $organizationSetting->date_format = 'D d M Y';
            }
            elseif($organizationSetting->date_format === 'D-M-Y'){
                $organizationSetting->date_format = 'D d M Y';
            }
            elseif($organizationSetting->date_format === 'D M Y'){
                $organizationSetting->date_format = 'D d M Y';
            }
            $organizationSetting->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

}
