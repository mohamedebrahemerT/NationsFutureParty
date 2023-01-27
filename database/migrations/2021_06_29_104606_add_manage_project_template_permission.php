<?php

use App\Models\Permission;
use App\Models\PermissionType;
use App\Models\ProjectRating;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddManageProjectTemplatePermission extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $projectModule = \App\Models\Module::firstOrCreate(['module_name' => 'projects']);
        $admins = RoleUser::where('role_id', '1')->get();
        $allTypePermisison = PermissionType::where('name', 'all')->first();

        $perm = Permission::create([
            'name' => 'manage_project_template',
            'display_name' => ucwords(str_replace('_', ' ', 'manage_project_template')),
            'is_custom' => 1,
            'module_id' => $projectModule->id
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $projectModule = \App\Models\Module::firstOrCreate(['module_name' => 'projects']);
        Permission::where('module_id', $projectModule->id)->where('is_custom', 1)->where('name', 'manage_project_template')->delete();
    }

}
