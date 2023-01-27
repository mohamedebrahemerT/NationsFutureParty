<?php

use App\Models\Module;
use App\Models\ModuleSetting;
use App\Models\Permission;
use App\Models\PermissionType;
use App\Models\RoleUser;
use App\Models\UserPermission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNoticeModuleClients extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        ModuleSetting::create([
            'module_name' => 'notices',
            'type' => 'client',
            'status' => 'active'
        ]);

        Module::where('module_name', 'notice board')->update(['module_name' => 'notices']);

        $orderModule = Module::where('module_name', 'orders')->first();
        $allPermissions = Permission::where('module_id', $orderModule->id)->get();
        $admins = RoleUser::where('role_id', '1')->get();
        $allTypePermisison = PermissionType::where('name', 'all')->first();

        foreach ($allPermissions as $permission) {
            foreach ($admins as $item) {
                UserPermission::create(
                [
                    'user_id' => $item->user_id,
                    'permission_id' => $permission->id,
                    'permission_type_id' => $allTypePermisison->id
                ]
                );
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        ModuleSetting::where('module_name', 'notices')->where('type', 'client')->delete();
    }

}
