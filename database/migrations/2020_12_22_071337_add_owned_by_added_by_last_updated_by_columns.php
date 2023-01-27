<?php

use App\Models\ClientContact;
use App\Models\ClientDetails;
use App\Models\Designation;
use App\Models\EmployeeDetails;
use App\Models\EmployeeDocument;
use App\Models\Permission;
use App\Models\PermissionType;
use App\Models\RoleUser;
use App\Models\Team;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOwnedByAddedByLastUpdatedByColumns extends Migration
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

        Schema::table('client_details', function (Blueprint $table) {
            $table->integer('added_by')->unsigned()->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');

            $table->integer('last_updated_by')->unsigned()->nullable();
            $table->foreign('last_updated_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');
        });

        Schema::table('client_contacts', function (Blueprint $table) {
            $table->integer('added_by')->unsigned()->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');

            $table->integer('last_updated_by')->unsigned()->nullable();
            $table->foreign('last_updated_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');
        });

        if (!is_null($admin)) {
            ClientDetails::whereNull('added_by')->update(['added_by' => $admin->id]);
            ClientDetails::whereNull('last_updated_by')->update(['last_updated_by' => $admin->id]);

            ClientContact::whereNull('added_by')->update(['added_by' => $admin->id]);
            ClientContact::whereNull('last_updated_by')->update(['last_updated_by' => $admin->id]);
        }

        Schema::table('employee_details', function (Blueprint $table) {
            $table->integer('added_by')->unsigned()->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');

            $table->integer('last_updated_by')->unsigned()->nullable();
            $table->foreign('last_updated_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');
        });


        if (!is_null($admin)) {
            EmployeeDetails::whereNull('added_by')->update(['added_by' => $admin->id]);
            EmployeeDetails::whereNull('last_updated_by')->update(['last_updated_by' => $admin->id]);
        }


        Schema::table('designations', function (Blueprint $table) {
            $table->integer('added_by')->unsigned()->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');

            $table->integer('last_updated_by')->unsigned()->nullable();
            $table->foreign('last_updated_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');
        });


        if (!is_null($admin)) {
            Designation::whereNull('added_by')->update(['added_by' => $admin->id]);
            Designation::whereNull('last_updated_by')->update(['last_updated_by' => $admin->id]);
        }


        Schema::table('teams', function (Blueprint $table) {
            $table->integer('added_by')->unsigned()->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');

            $table->integer('last_updated_by')->unsigned()->nullable();
            $table->foreign('last_updated_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');
        });


        if (!is_null($admin)) {
            Team::whereNull('added_by')->update(['added_by' => $admin->id]);
            Team::whereNull('last_updated_by')->update(['last_updated_by' => $admin->id]);
        }


        Schema::table('employee_docs', function (Blueprint $table) {
            $table->integer('added_by')->unsigned()->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');

            $table->integer('last_updated_by')->unsigned()->nullable();
            $table->foreign('last_updated_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');
        });


        if (!is_null($admin)) {
            EmployeeDocument::whereNull('added_by')->update(['added_by' => $admin->id]);
            EmployeeDocument::whereNull('last_updated_by')->update(['last_updated_by' => $admin->id]);
        }


        $clientCustomPermisisons = [
            'manage_client_category',

            'manage_client_subcategory',

            'add_client_contacts',
            'view_client_contacts',
            'edit_client_contacts',
            'delete_client_contacts'
        ];

        foreach ($clientCustomPermisisons as $permission) {
            $perm = Permission::create([
                'name' => $permission,
                'display_name' => ucwords(str_replace('_', ' ', $permission)),
                'is_custom' => 1,
                'module_id' => 1
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

        $employeeCustomPermissions = [
            'add_designation',
            'view_designation',
            'edit_designation',
            'delete_designation',

            'add_department',
            'view_department',
            'edit_department',
            'delete_department',

            'add_documents',
            'view_documents',
            'edit_documents',
            'delete_documents',

            'view_leaves_taken',
            'update_leaves_quota',
            'view_employee_tasks',
            'view_employee_projects',
            'view_employee_timelogs',

        ];

        foreach ($employeeCustomPermissions as $permission) {
            $perm = Permission::create([
                'name' => $permission,
                'display_name' => ucwords(str_replace('_', ' ', $permission)),
                'is_custom' => 1,
                'module_id' => 2
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
        Permission::where('module_id', 1)->where('is_custom', 1)->delete();
        Permission::where('module_id', 2)->where('is_custom', 1)->delete();

        Schema::table('client_details', function (Blueprint $table) {
            $table->dropForeign(['added_by']);
            $table->dropForeign(['last_updated_by']);
            $table->dropColumn(['added_by']);
            $table->dropColumn(['last_updated_by']);
        });

        Schema::table('employee_details', function (Blueprint $table) {
            $table->dropForeign(['added_by']);
            $table->dropForeign(['last_updated_by']);
            $table->dropColumn(['added_by']);
            $table->dropColumn(['last_updated_by']);
        });

        Schema::table('designations', function (Blueprint $table) {
            $table->dropForeign(['added_by']);
            $table->dropForeign(['last_updated_by']);
            $table->dropColumn(['added_by']);
            $table->dropColumn(['last_updated_by']);
        });

        Schema::table('teams', function (Blueprint $table) {
            $table->dropForeign(['added_by']);
            $table->dropForeign(['last_updated_by']);
            $table->dropColumn(['added_by']);
            $table->dropColumn(['last_updated_by']);
        });

        Schema::table('employee_docs', function (Blueprint $table) {
            $table->dropForeign(['added_by']);
            $table->dropForeign(['last_updated_by']);
            $table->dropColumn(['added_by']);
            $table->dropColumn(['last_updated_by']);
        });

        Schema::table('client_contacts', function (Blueprint $table) {
            $table->dropForeign(['added_by']);
            $table->dropForeign(['last_updated_by']);
            $table->dropColumn(['added_by']);
            $table->dropColumn(['last_updated_by']);
        });
    }

}
