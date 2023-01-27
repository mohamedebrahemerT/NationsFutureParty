<?php

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;

class SyncDefaultRolePermission extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1 - 'added'
        // 2 - 'owned'
        // 3 - 'both'
        // 4 - 'all'
        // 5 - 'none'

        $allPermissions = Permission::orderBy('id', 'asc')->get()->pluck('id')->toArray();

        $adminRole = Role::where('name', 'admin')->first();
        $employeeRole = Role::where('name', 'employee')->first();
        $clientRole = Role::where('name', 'client')->first();

        if ($adminRole) {
            $adminRole->perms()->sync([]);
            $adminRole->attachPermissions($allPermissions);
        }

        if ($employeeRole) {
            $employeeRole->perms()->sync([]);
            $employeeRole->attachPermissions($allPermissions);
        }

        if ($clientRole) {
            $clientRole->perms()->sync([]);
            $clientRole->attachPermissions($allPermissions);
        }


        $employees = User::allEmployees();

        foreach ($employees as $key => $user) {
            $user->permissionTypes()->sync([]);

            foreach ($allPermissions as $key => $permission) {
                $user->permissionTypes()->attach([$permission => ['permission_type_id' => 1]]);
            }
        }


        $admins = User::allAdmins();

        foreach ($admins as $key => $user) {
            $user->permissionTypes()->sync([]);

            foreach ($allPermissions as $key => $permission) {
                $user->permissionTypes()->attach([$permission => ['permission_type_id' => 4]]);
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
        //
    }

}
