<?php

use App\Models\Discussion;
use App\Models\Permission;
use App\Models\PermissionType;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\ProjectFile;
use App\Models\ProjectMember;
use App\Models\ProjectMilestone;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOwnedByAddedByLastUpdatedByColumnsProjects extends Migration
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

        Schema::table('projects', function (Blueprint $table) {
            $table->integer('added_by')->unsigned()->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');

            $table->integer('last_updated_by')->unsigned()->nullable();
            $table->foreign('last_updated_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');
        });

        Schema::table('project_files', function (Blueprint $table) {
            $table->integer('added_by')->unsigned()->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');

            $table->integer('last_updated_by')->unsigned()->nullable();
            $table->foreign('last_updated_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');
        });

        Schema::table('project_category', function (Blueprint $table) {
            $table->integer('added_by')->unsigned()->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');

            $table->integer('last_updated_by')->unsigned()->nullable();
            $table->foreign('last_updated_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');
        });

        Schema::table('project_members', function (Blueprint $table) {
            $table->integer('added_by')->unsigned()->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');

            $table->integer('last_updated_by')->unsigned()->nullable();
            $table->foreign('last_updated_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');
        });

        Schema::table('project_milestones', function (Blueprint $table) {
            $table->integer('added_by')->unsigned()->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');

            $table->integer('last_updated_by')->unsigned()->nullable();
            $table->foreign('last_updated_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');
        });

        Schema::table('discussions', function (Blueprint $table) {
            $table->integer('added_by')->unsigned()->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');

            $table->integer('last_updated_by')->unsigned()->nullable();
            $table->foreign('last_updated_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');
        });

        if (!is_null($admin)) {
            Project::whereNull('added_by')->update(['added_by' => $admin->id]);
            Project::whereNull('last_updated_by')->update(['last_updated_by' => $admin->id]);

            ProjectFile::whereNull('added_by')->update(['added_by' => $admin->id]);
            ProjectFile::whereNull('last_updated_by')->update(['last_updated_by' => $admin->id]);

            ProjectCategory::whereNull('added_by')->update(['added_by' => $admin->id]);
            ProjectCategory::whereNull('last_updated_by')->update(['last_updated_by' => $admin->id]);

            ProjectMember::whereNull('added_by')->update(['added_by' => $admin->id]);
            ProjectMember::whereNull('last_updated_by')->update(['last_updated_by' => $admin->id]);

            ProjectMilestone::whereNull('added_by')->update(['added_by' => $admin->id]);
            ProjectMilestone::whereNull('last_updated_by')->update(['last_updated_by' => $admin->id]);

            Discussion::whereNull('added_by')->update(['added_by' => $admin->id]);
            Discussion::whereNull('last_updated_by')->update(['last_updated_by' => $admin->id]);
        }

        $projectCustomPermisisons = [
            'manage_project_category',

            'view_project_files',
            'add_project_files',
            'edit_project_files',
            'delete_project_files',

            'view_project_discussions',
            'add_project_discussions',
            'edit_project_discussions',
            'delete_project_discussions',
            'manage_discussion_category',

            'view_project_milestones',
            'add_project_milestones',
            'edit_project_milestones',
            'delete_project_milestones',

            'view_project_members',
            'add_project_members',
            'edit_project_members',
            'delete_project_members',

            'view_project_rating',
            'add_project_rating',
            'edit_project_rating',
            'delete_project_rating',

            'view_project_budget',
            'view_project_timelogs',
            'view_project_expenses',
            'view_project_tasks',
            'view_project_invoices',
            'view_project_burndown_chart',
            'view_project_payments',
            'view_project_gantt_chart',

        ];

        foreach ($projectCustomPermisisons as $permission) {
            $perm = Permission::create([
                'name' => $permission,
                'display_name' => ucwords(str_replace('_', ' ', $permission)),
                'is_custom' => 1,
                'module_id' => 3
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
        Permission::where('module_id', 3)->where('is_custom', 1)->delete();

        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['added_by']);
            $table->dropForeign(['last_updated_by']);
            $table->dropColumn(['added_by']);
            $table->dropColumn(['last_updated_by']);
        });

        Schema::table('project_files', function (Blueprint $table) {
            $table->dropForeign(['added_by']);
            $table->dropForeign(['last_updated_by']);
            $table->dropColumn(['added_by']);
            $table->dropColumn(['last_updated_by']);
        });


        Schema::table('project_category', function (Blueprint $table) {
            $table->dropForeign(['added_by']);
            $table->dropForeign(['last_updated_by']);
            $table->dropColumn(['added_by']);
            $table->dropColumn(['last_updated_by']);
        });

        Schema::table('project_members', function (Blueprint $table) {
            $table->dropForeign(['added_by']);
            $table->dropForeign(['last_updated_by']);
            $table->dropColumn(['added_by']);
            $table->dropColumn(['last_updated_by']);
        });

        Schema::table('project_milestones', function (Blueprint $table) {
            $table->dropForeign(['added_by']);
            $table->dropForeign(['last_updated_by']);
            $table->dropColumn(['added_by']);
            $table->dropColumn(['last_updated_by']);
        });

        Schema::table('discussions', function (Blueprint $table) {
            $table->dropForeign(['added_by']);
            $table->dropForeign(['last_updated_by']);
            $table->dropColumn(['added_by']);
            $table->dropColumn(['last_updated_by']);
        });

    }

}
