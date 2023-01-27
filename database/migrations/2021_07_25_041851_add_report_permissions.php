<?php

use App\Models\Module;
use App\Models\ModuleSetting;
use App\Models\Permission;
use App\Models\PermissionType;
use App\Models\RoleUser;
use App\Models\UserPermission;
use Illuminate\Database\Migrations\Migration;

class AddReportPermissions extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $module = new Module();
        $module->module_name = 'reports';
        $module->description = 'User can manage permission of particular report';
        $module->save();

        Permission::insert([
            ['name' => 'view_task_report', 'display_name' => 'View Task Report', 'module_id' => $module->id, 'is_custom' => 1],
            ['name' => 'view_time_log_report', 'display_name' => 'View Time Log Report', 'module_id' => $module->id, 'is_custom' => 1],
            ['name' => 'view_finance_report', 'display_name' => 'View Finance Report', 'module_id' => $module->id, 'is_custom' => 1],
            ['name' => 'view_income_expense_report', 'display_name' => 'View Income Vs Expense Report', 'module_id' => $module->id, 'is_custom' => 1],
            ['name' => 'view_leave_report', 'display_name' => 'View Leave Report', 'module_id' => $module->id, 'is_custom' => 1],
            ['name' => 'view_attendance_report', 'display_name' => 'View Attendance Report', 'module_id' => $module->id, 'is_custom' => 1],
        ]);

        $modules = [
            ['module_name' => 'reports', 'status' => 'active', 'type' => 'admin'],
            ['module_name' => 'reports', 'status' => 'active', 'type' => 'employee']
        ];
        ModuleSetting::insert($modules);

        $module = new Module();
        $module->module_name = 'settings';
        $module->description = 'User can manage settings';
        $module->save();

        Permission::insert([
            ['name' => 'manage_company_setting', 'display_name' => 'Manage Company Settings', 'module_id' => $module->id, 'is_custom' => 1],
            ['name' => 'manage_app_setting', 'display_name' => 'Manage App Settings', 'module_id' => $module->id, 'is_custom' => 1],
            ['name' => 'manage_notification_setting', 'display_name' => 'Manage Notification Settings', 'module_id' => $module->id, 'is_custom' => 1],
            ['name' => 'manage_currency_setting', 'display_name' => 'Manage Currency Settings', 'module_id' => $module->id, 'is_custom' => 1],
            ['name' => 'manage_payment_setting', 'display_name' => 'Manage Payment Settings', 'module_id' => $module->id, 'is_custom' => 1],
            ['name' => 'manage_finance_setting', 'display_name' => 'Manage Finance Settings', 'module_id' => $module->id, 'is_custom' => 1],
            ['name' => 'manage_ticket_setting', 'display_name' => 'Manage Ticket Settings', 'module_id' => $module->id, 'is_custom' => 1],
            ['name' => 'manage_project_setting', 'display_name' => 'Manage Project Settings', 'module_id' => $module->id, 'is_custom' => 1],
            ['name' => 'manage_attendance_setting', 'display_name' => 'Manage Attendance Settings', 'module_id' => $module->id, 'is_custom' => 1],
            ['name' => 'manage_leave_setting', 'display_name' => 'Manage Leave Settings', 'module_id' => $module->id, 'is_custom' => 1],
            ['name' => 'manage_custom_field_setting', 'display_name' => 'Manage Custom Field Settings', 'module_id' => $module->id, 'is_custom' => 1],
            ['name' => 'manage_message_setting', 'display_name' => 'Manage Message Settings', 'module_id' => $module->id, 'is_custom' => 1],
            ['name' => 'manage_storage_setting', 'display_name' => 'Manage Storage Settings', 'module_id' => $module->id, 'is_custom' => 1],
            ['name' => 'manage_language_setting', 'display_name' => 'Manage Language Settings', 'module_id' => $module->id, 'is_custom' => 1],
            ['name' => 'manage_lead_setting', 'display_name' => 'Manage Lead Settings', 'module_id' => $module->id, 'is_custom' => 1],
            ['name' => 'manage_time_log_setting', 'display_name' => 'Manage Time Log Settings', 'module_id' => $module->id, 'is_custom' => 1],
            ['name' => 'manage_task_setting', 'display_name' => 'Manage Task Settings', 'module_id' => $module->id, 'is_custom' => 1],
            ['name' => 'manage_social_login_setting', 'display_name' => 'Manage Social Login Settings', 'module_id' => $module->id, 'is_custom' => 1],
            ['name' => 'manage_security_setting', 'display_name' => 'Manage Security Settings', 'module_id' => $module->id, 'is_custom' => 1],
            ['name' => 'manage_gdpr_setting', 'display_name' => 'Manage GDPR Settings', 'module_id' => $module->id, 'is_custom' => 1],
            ['name' => 'manage_theme_setting', 'display_name' => 'Manage Theme Settings', 'module_id' => $module->id, 'is_custom' => 1]
        ]);

        $modules = [
            ['module_name' => 'settings', 'status' => 'active', 'type' => 'admin'],
            ['module_name' => 'settings', 'status' => 'active', 'type' => 'employee']
        ];
        ModuleSetting::insert($modules);

        $permissions = ['view_task_report', 'view_time_log_report', 'finance', 'view_income_expense_report', 'view_leave_report', 'view_attendance_report', 'manage_company_setting', 'manage_app_setting', 'manage_notification_setting', 'manage_currency_setting', 'manage_payment_setting', 'manage_finance_setting', 'manage_ticket_setting', 'manage_project_setting', 'manage_attendance_setting', 'manage_leave_setting', 'manage_custom_field_setting', 'manage_message_setting', 'manage_storage_setting', 'manage_language_setting', 'manage_lead_setting', 'manage_time_log_setting', 'manage_task_setting', 'manage_social_login_setting', 'manage_security_setting', 'manage_gdpr_setting', 'manage_theme_setting'];


        $allPermissions = Permission::whereIn('name', $permissions)->get();
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
        $module = Module::where('module_name', 'reports')->first();
        $settingsmodule = Module::where('module_name', 'settings')->first();

        Permission::where('module_id', $module->id)->delete();
        Permission::where('module_id', $settingsmodule->id)->delete();

        Module::where('module_name', 'reports')->delete();
        Module::where('module_name', 'settings')->delete();
    }

}
