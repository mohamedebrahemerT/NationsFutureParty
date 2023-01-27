<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class EmployeePermissionSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissionTypes = [
            'added' => 1,
            'owned' => 2,
            'both' => 3,
            'all' => 4,
            'none' => 5
        ];

        // Employee role
        $employeeRole = Role::with('roleuser', 'roleuser.user.roles')->where('name', 'employee')->first();
        $allPermissions = Permission::all();

        PermissionRole::where('role_id', $employeeRole->id)->delete();
        $employeePermissionsArray = PermissionRole::employeeRolePermissions();

        foreach ($allPermissions as $key => $value) {
            $permissionRole = new PermissionRole();
            $permissionRole->permission_id = $value->id;
            $permissionRole->role_id = $employeeRole->id;
            $permissionRole->permission_type_id = $permissionTypes['none'];
            $permissionRole->save();
        }

        $employeePermissionsArrayKeys = array_keys($employeePermissionsArray);

        $employeePermissions = Permission::whereIn('name', $employeePermissionsArrayKeys)->get();

        foreach ($employeePermissions as $key => $ep) {

            $permissionRole = PermissionRole::with('permission')
                ->where('permission_id', $ep->id)
                ->where('role_id', $employeeRole->id)
                ->first();
            PermissionRole::where('permission_id', $ep->id)
                ->where('role_id', $employeeRole->id)
                ->update(['permission_type_id' => $permissionTypes[$employeePermissionsArray[$permissionRole->permission->name]]]);
        }

        // Employee permissions will be synced via cron
        $userIds = $employeeRole->roleuser->pluck('user_id');
        try {
            User::whereIn('id', $userIds)->update(['permission_sync' => 0]);
        } catch (\Exception $exception) {
            Log::info($exception);
        }


        // Admin role
        $adminRole = Role::with('roleuser', 'roleuser.user.roles')->where('name', 'admin')->first();
        $allPermissions = Permission::all();
        PermissionRole::where('role_id', $adminRole->id)->delete();

        foreach ($allPermissions as $key => $value) {
            $permissionRole = new PermissionRole();
            $permissionRole->permission_id = $value->id;
            $permissionRole->role_id = $adminRole->id;
            $permissionRole->permission_type_id = $permissionTypes['all'];
            $permissionRole->save();
        }

        foreach ($adminRole->roleuser as $roleuser) {
            $roleuser->user->assignUserRolePermission($adminRole->id);
        }

        // Client role
        $clientRole = Role::with('roleuser', 'roleuser.user.roles')->where('name', 'client')->first();
        $allPermissions = Permission::all();

        $clientPermissionsArray = PermissionRole::clientRolePermissions();

        PermissionRole::where('role_id', $clientRole->id)->delete();

        foreach ($allPermissions as $key => $value) {
            $permissionRole = new PermissionRole();
            $permissionRole->permission_id = $value->id;
            $permissionRole->role_id = $clientRole->id;
            $permissionRole->permission_type_id = $permissionTypes['none'];
            $permissionRole->save();
        }

        $clientPermissionsArrayKeys = array_keys($clientPermissionsArray);

        $clientPermissions = Permission::whereIn('name', $clientPermissionsArrayKeys)->get();

        foreach ($clientPermissions as $key => $ep) {

            $permissionRole = PermissionRole::with('permission')
                ->where('permission_id', $ep->id)
                ->where('role_id', $clientRole->id)
                ->first();
            PermissionRole::where('permission_id', $ep->id)
                ->where('role_id', $clientRole->id)
                ->update(['permission_type_id' => $permissionTypes[$clientPermissionsArray[$permissionRole->permission->name]]]);
        }

        foreach ($clientRole->roleuser as $roleuser) {
            $roleuser->user->assignUserRolePermission($clientRole->id);
        }


    }

}
