<?php

use App\Models\ModuleSetting;
use Illuminate\Database\Migrations\Migration;

class AddTaskModuleInModuleSettingTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $modules = ModuleSetting::where('module_name', 'tasks')->where('type', 'client')->get();

        if ($modules->count() == 0){
            $module = new ModuleSetting();
            $module->type = 'client';
            $module->module_name = 'tasks';
            $module->status = 'active';
            $module->save();
        }
    }

}
