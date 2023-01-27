<?php

use App\Models\Module;
use App\Models\ModuleSetting;
use App\Models\Permission;
use App\Models\PermissionType;
use App\Models\RoleUser;
use App\Models\UserPermission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDashboardModulePermission extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $module = new Module();
        $module->module_name = 'dashboards';
        $module->save();

        $admins = RoleUser::where('role_id', '1')->get();
        $allTypePermisison = PermissionType::where('name', 'all')->first();

        $dashboardPermisisons = [
            'view_overview_dashboard',
            'view_project_dashboard',
            'view_client_dashboard',
            'view_hr_dashboard',
            'view_ticket_dashboard',
            'view_finance_dashboard'
        ];

        foreach ($dashboardPermisisons as $permission) {
            $perm = Permission::create([
                'name' => $permission,
                'display_name' => ucwords(str_replace('_', ' ', $permission)),
                'is_custom' => 1,
                'allowed_permissions' => '{"all":4, "none":5}',
                'module_id' => $module->id
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

        ModuleSetting::create(
            [
                'module_name' => 'dashboards',
                'type' => 'employee',
                'status' => 'deactive'
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $settingsmodule = Module::where('module_name', 'dashboards')->first();

        Permission::where('module_id', $settingsmodule->id)->delete();
        Module::where('module_name', 'dashboards')->delete();
        ModuleSetting::where('module_name', 'dashboards')->delete();
    }

}
