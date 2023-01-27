<?php

use Illuminate\Database\Migrations\Migration;

class AddHolidayModule extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $moduleSetting = \App\Models\ModuleSetting::where('module_name', 'holidays')->first();

        if(!$moduleSetting){
            $module = new \App\Models\ModuleSetting();
            $module->module_name = 'holidays';
            $module->status = 'active';
            $module->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }

}
