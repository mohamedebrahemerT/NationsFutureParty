<?php
use Illuminate\Database\Migrations\Migration;

class InsertModuleSettingClientTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\Models\ModuleSetting::where('type', 'client')->delete();
        $clientModules = ['projects', 'tickets', 'invoices', 'estimates', 'events', 'messages'];

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
        //
    }

}
