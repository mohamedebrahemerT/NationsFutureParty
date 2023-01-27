<?php
use Illuminate\Database\Migrations\Migration;

class InsertModuleSettingClientTaskTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\Models\ModuleSetting::where('type', 'client')->where('module_name', 'tasks')->delete();
        $clientModules = ['tasks'];

        foreach($clientModules as $moduleSetting){
                $modulesClient = new \App\Models\ModuleSetting();
                $modulesClient->module_name = $moduleSetting;
                $modulesClient->type = 'client';
                $modulesClient->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\Models\ModuleSetting::where('type', 'client')->where('module_name', 'tasks')->delete();
    }

}
