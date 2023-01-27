<?php

use Illuminate\Database\Migrations\Migration;

class AddTimelogsClientModules extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \App\Models\ModuleSetting::where('type', 'client')->where('module_name', 'timelogs')->delete();
        $clientModules = ['timelogs'];

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
