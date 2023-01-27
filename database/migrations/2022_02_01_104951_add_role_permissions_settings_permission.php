<?php

use App\Models\Module;
use App\Models\Permission;
use App\Models\PermissionType;
use App\Models\RoleUser;
use App\Models\UserPermission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRolePermissionsSettingsPermission extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $admins = RoleUser::where('role_id', '1')->get();
        $allTypePermisison = PermissionType::where('name', 'all')->first();
        $module = Module::where('module_name', 'settings')->first();

        $employeeCustomPermisisons = [
            'manage_role_permission_setting',
            'manage_module_setting',
            'manage_google_calendar_setting',
        ];

        foreach ($employeeCustomPermisisons as $permission) {
            $perm = Permission::create([
                'name' => $permission,
                'display_name' => ucwords(str_replace('_', ' ', $permission)),
                'is_custom' => 1,
                'module_id' => $module->id,
                'allowed_permissions' => '{"all":4, "none":5}'
            ]);

            foreach ($admins as $item) {
                UserPermission::create(
                    [
                        'user_id' => $item->user_id,
                        'permission_id' => $perm->id,
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
        Permission::where('name', 'manage_role_permission_setting')->delete();
        Permission::where('name', 'manage_module_setting')->delete();
        Permission::where('name', 'manage_google_calendar_setting')->delete();
    }

}
