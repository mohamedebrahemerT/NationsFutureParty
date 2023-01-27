<?php

use App\Models\Role;
use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\RoleUser;
use App\Models\UserPermission;
use Google\Service\Dfareporting\UserRole;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultPermissionToKnowledgeBasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {

        $permissionTypes = [
            'added' => 1,
            'owned' => 2,
            'both' => 3,
            'all' => 4,
            'none' => 5
        ];

        $employeeRoles = Role::where('name', 'employee')->orWhere('name', 'client')->orWhere('name', 'admin')->get(['id', 'name']);

        if($employeeRoles->count() == 3)
        {

            foreach($employeeRoles as $employeeRole){
                // IF Role is Admin
                if($employeeRole->name == 'admin')
                {
                    $this->assignPermissionToAdmin($employeeRole, $permissionTypes);
                }
                else if($employeeRole->name == 'employee' || $employeeRole->name == 'client')
                {
                    // Assign view permission to client and employee default
                    $viewPermission = Permission::where('name', 'view_knowledgebase')->get('id');
                    $isAlreadyPresent = PermissionRole::where('role_id', $employeeRole->id)->where('permission_id', $viewPermission[0]['id'])->count();


                    // If permission is not Assinged for knowledgebase
                    if($isAlreadyPresent != 1)
                    {
                        $addNew = new PermissionRole();
                        $addNew->permission_id = $viewPermission[0]['id'];
                        $addNew->role_id = $employeeRole->id;
                        $addNew->permission_type_id = $permissionTypes['all'];
                        $addNew->save();
                    }
                    else
                    {
                        PermissionRole::where('role_id', $employeeRole->id)
                            ->where('permission_id', $viewPermission[0]['id'])
                            ->update(['permission_type_id' => $permissionTypes['all']]);
                    }

                    // Get all user id of assinged roles
                    $roles = RoleUser::where('role_id', $employeeRole->id)->get('user_id');

                    foreach($roles as $role)
                    {
                        // Check if permission assign to user
                        $isUserAssigned = UserPermission::where('user_id', $role->user_id)->where('permission_id', $viewPermission[0]['id'])->where('permission_type_id', $permissionTypes['all'])->count();

                        if($isUserAssigned != 1)
                        {
                            $userPermission = new UserPermission();
                            $userPermission->user_id = $role->user_id;
                            $userPermission->permission_id = $viewPermission[0]['id'];
                            $userPermission->permission_type_id = $permissionTypes['all'];
                            $userPermission->save();
                        }
                        else if($isUserAssigned == 1)
                        {
                            UserPermission::where('user_id', $role->user_id)
                                ->where('permission_id', $viewPermission[0]['id'])
                                ->update(['permission_type_id' => $permissionTypes['all']]);
                        }

                    }
                }
            }

        }

    }

    public function assignPermissionToAdmin($employeeRole, $permissionTypes)
    {
        // Get all user id of admin role
        $adminId = RoleUser::where('role_id', $employeeRole->id)->get('user_id');
        // Get All Permissions
        $allPermissions = Permission::where('name', 'view_knowledgebase')
            ->orWhere('name', 'add_knowledgebase')
            ->orWhere('name', 'edit_knowledgebase')
            ->orWhere('name', 'delete_knowledgebase')
            ->get(['id','name']);

        foreach($allPermissions as $permission)
        {
            // Check if permission alredy assigned to Admin
            $isAlready = PermissionRole::where('role_id', $employeeRole->id)->where('permission_id', $permission->id)->count();

            // If permission is not Assinged for knowledgebase
            if($isAlready != 1)
            {
                $addNew = new PermissionRole();
                $addNew->permission_id = $permission->id;
                $addNew->role_id = $employeeRole->id;
                $addNew->permission_type_id = $permissionTypes['all'];
                $addNew->save();
            }
            else
            {
                // Else update permission to all
                PermissionRole::where('role_id', $employeeRole->id)
                    ->where('permission_id', $permission->id)
                    ->update(['permission_type_id' => $permissionTypes['all']]);
            }


            foreach($adminId as $admin)
            {
                // Check if permission assign to user
                $isUserAssigned = UserPermission::where('user_id', $admin->user_id)->where('permission_id', $permission->id)->where('permission_type_id', $permissionTypes['all'])->count();

                if($isUserAssigned != 1)
                {
                    $userPermission = new UserPermission();
                    $userPermission->user_id = $admin->user_id;
                    $userPermission->permission_id = $permission->id;
                    $userPermission->permission_type_id = $permissionTypes['all'];
                    $userPermission->save();
                }
                else if($isUserAssigned == 1)
                {
                    UserPermission::where('user_id', $admin->user_id)
                        ->where('permission_id', $permission->id)
                        ->update(['permission_type_id' => $permissionTypes['all']]);
                }

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
        Schema::table('knowledge_base', function (Blueprint $table) {
            //
        });
    }

}
