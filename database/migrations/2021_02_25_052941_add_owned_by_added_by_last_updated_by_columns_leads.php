<?php

use App\Models\Lead;
use App\Models\LeadAgent;
use App\Models\LeadCategory;
use App\Models\LeadCustomForm;
use App\Models\LeadFiles;
use App\Models\LeadFollowUp;
use App\Models\LeadSource;
use App\Models\Permission;
use App\Models\PermissionType;
use App\Models\Proposal;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOwnedByAddedByLastUpdatedByColumnsLeads extends Migration
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

        Schema::table('leads', function (Blueprint $table) {
            $table->integer('added_by')->unsigned()->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');

            $table->integer('last_updated_by')->unsigned()->nullable();
            $table->foreign('last_updated_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');
        });

        Schema::table('lead_agents', function (Blueprint $table) {
            $table->integer('added_by')->unsigned()->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');

            $table->integer('last_updated_by')->unsigned()->nullable();
            $table->foreign('last_updated_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');
        });

        Schema::table('lead_category', function (Blueprint $table) {
            $table->integer('added_by')->unsigned()->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');

            $table->integer('last_updated_by')->unsigned()->nullable();
            $table->foreign('last_updated_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');
        });

        Schema::table('lead_custom_forms', function (Blueprint $table) {
            $table->integer('added_by')->unsigned()->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');

            $table->integer('last_updated_by')->unsigned()->nullable();
            $table->foreign('last_updated_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');
        });

        Schema::table('lead_files', function (Blueprint $table) {
            $table->integer('added_by')->unsigned()->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');

            $table->integer('last_updated_by')->unsigned()->nullable();
            $table->foreign('last_updated_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');
        });

        Schema::table('lead_follow_up', function (Blueprint $table) {
            $table->integer('added_by')->unsigned()->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');

            $table->integer('last_updated_by')->unsigned()->nullable();
            $table->foreign('last_updated_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');
        });

        Schema::table('lead_sources', function (Blueprint $table) {
            $table->integer('added_by')->unsigned()->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');

            $table->integer('last_updated_by')->unsigned()->nullable();
            $table->foreign('last_updated_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');
        });

        Schema::table('proposals', function (Blueprint $table) {
            $table->integer('added_by')->unsigned()->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');

            $table->integer('last_updated_by')->unsigned()->nullable();
            $table->foreign('last_updated_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');
        });


        if (!is_null($admin)) {
            Lead::whereNull('added_by')->update(['added_by' => $admin->id]);
            Lead::whereNull('last_updated_by')->update(['last_updated_by' => $admin->id]);

            LeadAgent::whereNull('added_by')->update(['added_by' => $admin->id]);
            LeadAgent::whereNull('last_updated_by')->update(['last_updated_by' => $admin->id]);

            LeadCategory::whereNull('added_by')->update(['added_by' => $admin->id]);
            LeadCategory::whereNull('last_updated_by')->update(['last_updated_by' => $admin->id]);

            LeadCustomForm::whereNull('added_by')->update(['added_by' => $admin->id]);
            LeadCustomForm::whereNull('last_updated_by')->update(['last_updated_by' => $admin->id]);

            LeadFiles::whereNull('added_by')->update(['added_by' => $admin->id]);
            LeadFiles::whereNull('last_updated_by')->update(['last_updated_by' => $admin->id]);

            LeadFollowUp::whereNull('added_by')->update(['added_by' => $admin->id]);
            LeadFollowUp::whereNull('last_updated_by')->update(['last_updated_by' => $admin->id]);

            LeadSource::whereNull('added_by')->update(['added_by' => $admin->id]);
            LeadSource::whereNull('last_updated_by')->update(['last_updated_by' => $admin->id]);

            Proposal::whereNull('added_by')->update(['added_by' => $admin->id]);
            Proposal::whereNull('last_updated_by')->update(['last_updated_by' => $admin->id]);
        }


        $leadCustomPermisisons = [
            'view_lead_agents',
            'add_lead_agent',
            'edit_lead_agent',
            'delete_lead_agent',

            'view_lead_category',
            'add_lead_category',
            'edit_lead_category',
            'delete_lead_category',

            'manage_lead_custom_forms',

            'view_lead_files',
            'add_lead_files',
            'edit_lead_files',
            'delete_lead_files',

            'view_lead_follow_up',
            'add_lead_follow_up',
            'edit_lead_follow_up',
            'delete_lead_follow_up',

            'view_lead_sources',
            'add_lead_sources',
            'edit_lead_sources',
            'delete_lead_sources',

            'view_lead_proposals',
            'add_lead_proposals',
            'edit_lead_proposals',
            'delete_lead_proposals',

        ];

        foreach ($leadCustomPermisisons as $permission) {
            $perm = Permission::create([
                'name' => $permission,
                'display_name' => ucwords(str_replace('_', ' ', $permission)),
                'is_custom' => 1,
                'module_id' => 14
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
        Permission::where('module_id', 14)->where('is_custom', 1)->delete();

        Schema::table('lead_agents', function (Blueprint $table) {
            $table->dropForeign(['added_by']);
            $table->dropForeign(['last_updated_by']);
            $table->dropColumn(['added_by']);
            $table->dropColumn(['last_updated_by']);
        });

        Schema::table('lead_category', function (Blueprint $table) {
            $table->dropForeign(['added_by']);
            $table->dropForeign(['last_updated_by']);
            $table->dropColumn(['added_by']);
            $table->dropColumn(['last_updated_by']);
        });

        Schema::table('lead_custom_forms', function (Blueprint $table) {
            $table->dropForeign(['added_by']);
            $table->dropForeign(['last_updated_by']);
            $table->dropColumn(['added_by']);
            $table->dropColumn(['last_updated_by']);
        });

        Schema::table('lead_files', function (Blueprint $table) {
            $table->dropForeign(['added_by']);
            $table->dropForeign(['last_updated_by']);
            $table->dropColumn(['added_by']);
            $table->dropColumn(['last_updated_by']);
        });

        Schema::table('lead_follow_up', function (Blueprint $table) {
            $table->dropForeign(['added_by']);
            $table->dropForeign(['last_updated_by']);
            $table->dropColumn(['added_by']);
            $table->dropColumn(['last_updated_by']);
        });

        Schema::table('lead_sources', function (Blueprint $table) {
            $table->dropForeign(['added_by']);
            $table->dropForeign(['last_updated_by']);
            $table->dropColumn(['added_by']);
            $table->dropColumn(['last_updated_by']);
        });

        Schema::table('proposals', function (Blueprint $table) {
            $table->dropForeign(['added_by']);
            $table->dropForeign(['last_updated_by']);
            $table->dropColumn(['added_by']);
            $table->dropColumn(['last_updated_by']);
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign(['added_by']);
            $table->dropForeign(['last_updated_by']);
            $table->dropColumn(['added_by']);
            $table->dropColumn(['last_updated_by']);
        });
    }

}
