<?php

use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\PermissionType;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\UserPermission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddMissingPermissionsToAdminRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        $adminRole = Role::where('name', 'admin')->first();

        if ($adminRole) {

            $adminCurrentPermission = PermissionRole::where('role_id', $adminRole->id)->get()->pluck('permission_id');
            $adminMissingPermissions = Permission::whereNotIn('id', $adminCurrentPermission)->get();
            $allTypePermisison = PermissionType::where('name', 'all')->first();
            $admins = RoleUser::where('role_id', '1')->get();
            $allPermissions = Permission::get();

            // Adding all permissions to admin role user
            foreach ($admins as $item) {

                foreach ($allPermissions as $allPermission) {
                    UserPermission::firstOrCreate(
                        [
                            'user_id' => $item->user_id,
                            'permission_id' => $allPermission->id,
                            'permission_type_id' => $allTypePermisison->id
                        ]
                    );
                }
            }

            // Adding missing permissions to permission_role table

            foreach ($adminMissingPermissions as $adminMissingPermission) {
                $newPermission = new PermissionRole();
                $newPermission->permission_id = $adminMissingPermission->id;
                $newPermission->role_id = $adminRole->id;
                $newPermission->permission_type_id = $allTypePermisison->id;
                $newPermission->save();
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

    }

}
