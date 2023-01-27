<?php

use App\Models\Module;
use App\Models\Permission;
use App\Models\ModuleSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddModuleKnowledgebaseAndPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        $moduleCheck = Module::where('module_name', 'knowledgebase')->first();

        if (!$moduleCheck){
            $module = new Module();
            $module->module_name = 'knowledgebase';
            $module->save();
            $id = $module->id;
        }
        else {
            $id = $moduleCheck->id;
        }

        $modulesClient = new ModuleSetting();
        $modulesClient->module_name = 'knowledgebase';
        $modulesClient->type = 'client';
        $modulesClient->status = 'active';
        $modulesClient->save();

        $modulesClient = new ModuleSetting();
        $modulesClient->module_name = 'knowledgebase';
        $modulesClient->type = 'admin';
        $modulesClient->status = 'active';
        $modulesClient->save();

        $modulesClient = new ModuleSetting();
        $modulesClient->module_name = 'knowledgebase';
        $modulesClient->type = 'employee';
        $modulesClient->status = 'active';
        $modulesClient->save();


        Permission::insert([
            ['name' => 'add_knowledgebase', 'display_name' => 'Add Knowledgebase', 'module_id' => $id, 'allowed_permissions' => '{"all":4, "none":5}'],
            ['name' => 'view_knowledgebase', 'display_name' => 'View Knowledgebase', 'module_id' => $id, 'allowed_permissions' => '{"added":1, "all":4, "none":5}'],
            ['name' => 'edit_knowledgebase', 'display_name' => 'Edit Knowledgebase', 'module_id' => $id, 'allowed_permissions' => '{"added":1, "all":4, "none":5}'],
            ['name' => 'delete_knowledgebase', 'display_name' => 'Delete Knowledgebase', 'module_id' => $id, 'allowed_permissions' => '{"added":1, "all":4, "none":5}'],
        ]);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $moduleCheck = Module::where('module_name', 'knowledgebase')->first();

        if($moduleCheck){
            Permission::where('module_id', $moduleCheck->id)->delete();
        }

    }

}
