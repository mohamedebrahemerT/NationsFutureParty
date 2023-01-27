<?php

use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\UserPermission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDiscussionCategoryPermission extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permission = Permission::where('name', 'manage_discussion_category')->first();
        $permission->allowed_permissions = '{"all":4, "none":5}';
        $permission->save();

        PermissionRole::where('permission_id', $permission->id)->where('permission_type_id', 1)->update(['permission_type_id' => 4]);
        UserPermission::where('permission_id', $permission->id)->where('permission_type_id', 1)->update(['permission_type_id' => 4]);

        Permission::where('name', 'view_project_milestones')
            ->orWhere('name', 'edit_project_milestones')
            ->orWhere('name', 'delete_project_milestones')
            ->update(['allowed_permissions' => '{"all":4, "added":1, "owned":2, "none":5}']);

        $permission = Permission::where('name', 'view_project_members')->first();
        $permission->allowed_permissions = '{"all":4, "none":5}';
        $permission->save();

        PermissionRole::where('permission_id', $permission->id)->where('permission_type_id', 1)->update(['permission_type_id' => 4]);
        UserPermission::where('permission_id', $permission->id)->where('permission_type_id', 1)->update(['permission_type_id' => 4]);
    
        $permission = Permission::where('name', 'edit_project_members')->first();
        $permission->allowed_permissions = '{"all":4, "none":5}';
        $permission->save();

        PermissionRole::where('permission_id', $permission->id)->where('permission_type_id', 1)->update(['permission_type_id' => 4]);
        UserPermission::where('permission_id', $permission->id)->where('permission_type_id', 1)->update(['permission_type_id' => 4]);
    
        $permission = Permission::where('name', 'delete_project_members')->first();
        $permission->allowed_permissions = '{"all":4, "none":5}';
        $permission->save();

        PermissionRole::where('permission_id', $permission->id)->where('permission_type_id', 1)->update(['permission_type_id' => 4]);
        UserPermission::where('permission_id', $permission->id)->where('permission_type_id', 1)->update(['permission_type_id' => 4]);
    
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
