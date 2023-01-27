<?php

use App\Models\Attendance;
use App\Models\Permission;
use App\Models\PermissionType;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOwnedByAddedByLastUpdatedByColumnsAttendance extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $admin = User::allAdmins()->first();
        $admins = RoleUser::where('role_id', '1')->get();
        $allTypePermisison = PermissionType::where('name', 'all')->first();

        Schema::table('attendances', function (Blueprint $table) {
            $table->integer('added_by')->unsigned()->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');

            $table->integer('last_updated_by')->unsigned()->nullable();
            $table->foreign('last_updated_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');
        });

        if (!is_null($admin)) {
            Attendance::whereNull('added_by')->update(['added_by' => $admin->id]);
            Attendance::whereNull('last_updated_by')->update(['last_updated_by' => $admin->id]);
        }

        $permisisons = [
            'edit_attendance',
            'delete_attendance'
        ];

        $customPermisisons = [
            'manage_attendance'
        ];

        foreach ($permisisons as $permission) {
            $perm = Permission::create([
                'name' => $permission,
                'display_name' => ucwords(str_replace('_', ' ', $permission)),
                'module_id' => 4
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

        foreach ($customPermisisons as $permission) {
            $perm = Permission::create([
                'name' => $permission,
                'display_name' => ucwords(str_replace('_', ' ', $permission)),
                'is_custom' => 1,
                'module_id' => 4
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
        Permission::where('module_id', 4)->where('is_custom', 1)->delete();
        Permission::where('name', 'edit_attendance')->delete();
        Permission::where('name', 'delete_attendance')->delete();

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['added_by']);
            $table->dropForeign(['last_updated_by']);
            $table->dropColumn(['added_by']);
            $table->dropColumn(['last_updated_by']);
        });
    }

}
