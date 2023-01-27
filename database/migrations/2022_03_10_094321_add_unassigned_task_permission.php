<?php

use App\Models\Module;
use App\Models\Permission;
use App\Models\PermissionType;
use App\Models\RoleUser;
use App\Models\UserPermission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUnassignedTaskPermission extends Migration
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
        $module = Module::where('module_name', 'tasks')->first();

        $employeeCustomPermisisons = [
            'view_unassigned_tasks'
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

        Permission::whereIn('name', ['add_task_files', 'add_sub_tasks', 'add_task_comments', 'add_task_notes'])->update(['allowed_permissions' => '{"all":4, "added":1, "owned":2,"both":3, "none":5}']);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::where('name', 'view_unassigned_tasks')->delete();
    }

}
