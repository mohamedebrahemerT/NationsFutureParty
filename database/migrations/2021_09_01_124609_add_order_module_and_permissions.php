<?php

use App\Models\Module;
use App\Models\ModuleSetting;
use App\Models\Permission;
use Illuminate\Database\Migrations\Migration;

class AddOrderModuleAndPermissions extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $moduleCheck = Module::where('module_name', 'orders')->first();

        if (!$moduleCheck){
            $module = new Module();
            $module->module_name = 'orders';
            $module->save();
            $id = $module->id;
        }
        else {
            $id = $moduleCheck->id;
        }

        $modulesClient = new ModuleSetting();
        $modulesClient->module_name = 'orders';
        $modulesClient->type = 'client';
        $modulesClient->status = 'active';
        $modulesClient->save();

        $modulesClient = new ModuleSetting();
        $modulesClient->module_name = 'orders';
        $modulesClient->type = 'admin';
        $modulesClient->status = 'active';
        $modulesClient->save();


        Permission::insert([
            ['name' => 'add_order', 'display_name' => 'Add Order', 'module_id' => $id, 'allowed_permissions' => '{"all":4, "none":5}'],
            ['name' => 'view_order', 'display_name' => 'View Order', 'module_id' => $id, 'allowed_permissions' => '{"all":4, "owned":2, "none":5}'],
            ['name' => 'edit_order', 'display_name' => 'Edit Order', 'module_id' => $id, 'allowed_permissions' => '{"all":4, "owned":2, "none":5}'],
            ['name' => 'delete_order', 'display_name' => 'Delete Order', 'module_id' => $id, 'allowed_permissions' => '{"all":4, "owned":2, "none":5}'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $moduleCheck = Module::where('module_name', 'orders')->first();

        if($moduleCheck){
            Permission::where('module_id', $moduleCheck->id)->delete();
        }
    }

}
