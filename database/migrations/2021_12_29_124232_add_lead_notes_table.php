<?php

use App\Models\Permission;
use App\Models\PermissionType;
use App\Models\RoleUser;
use App\Models\UserPermission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLeadNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        $admins = RoleUser::where('role_id', '1')->get();
        $allTypePermisison = PermissionType::where('name', 'all')->first();

        Schema::create('lead_notes', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('lead_id')->unsigned()->nullable();
            $table->foreign('lead_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->string('title');

            $table->boolean('type')->default(0);

            $table->unsignedInteger('member_id')->nullable();
            $table->foreign('member_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->boolean('is_lead_show')->default(0);

            $table->boolean('ask_password')->default(0);
            $table->string('details');

            $table->integer('added_by')->unsigned()->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');

            $table->integer('last_updated_by')->unsigned()->nullable();
            $table->foreign('last_updated_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');

            $table->timestamps();
        });

        Schema::create('lead_user_notes', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->integer('lead_note_id')->unsigned();
            $table->foreign('lead_note_id')->references('id')->on('lead_notes')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });

        $clientCustomPermisisons = [
            'add_lead_note',
            'view_lead_note',
            'edit_lead_note',
            'delete_lead_note',
        ];

        foreach ($clientCustomPermisisons as $permission) {
            $perm = Permission::create([
                'name' => $permission,
                'display_name' => ucwords(str_replace('_', ' ', $permission)),
                'is_custom' => 1,
                'module_id' => 14,
                'allowed_permissions' => '{"all":4, "added":1, "owned":2,"both":3, "none":5}'
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
        Schema::dropIfExists('lead_user_notes');
        Schema::dropIfExists('lead_notes');
    }

}
