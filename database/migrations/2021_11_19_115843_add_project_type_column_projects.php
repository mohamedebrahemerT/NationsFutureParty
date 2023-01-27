<?php

use App\Models\Permission;
use App\Models\PermissionType;
use App\Models\RoleUser;
use App\Models\UserPermission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProjectTypeColumnProjects extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->boolean('public');
        });

        $admins = RoleUser::where('role_id', '1')->get();
        $allTypePermisison = PermissionType::where('name', 'all')->first();

        $projectCustomPermisisons = ['create_public_project'];

        foreach ($projectCustomPermisisons as $permission) {
            $perm = Permission::create([
                'name' => $permission,
                'display_name' => ucwords(str_replace('_', ' ', $permission)),
                'is_custom' => 1,
                'module_id' => 3,
                'allowed_permissions' => '{"all":4, "none":5}',
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
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('public');
        });
    }

}
