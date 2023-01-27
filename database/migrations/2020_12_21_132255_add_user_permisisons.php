<?php

use App\Models\Permission;
use App\Models\PermissionType;
use App\Models\RoleUser;
use App\Models\UserPermission;
use Illuminate\Database\Migrations\Migration;

class AddUserPermisisons extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $allPermissions = Permission::all();
        $allTypePermisison = PermissionType::where('name', 'all')->first();

        $admins = RoleUser::where('role_id', '1')->get();

        foreach ($admins as $admin) {
            foreach ($allPermissions as $permission) {
                UserPermission::create(
                    [
                        'user_id' => $admin->user_id,
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
        UserPermission::truncate();
    }

}
