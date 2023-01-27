<?php

use App\Models\Permission;
use App\Models\PermissionType;
use App\Models\RoleUser;
use App\Models\UserPermission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CraeteProjectNotesTable extends Migration
{

    public function up()
    {

        $admins = RoleUser::where('role_id', '1')->get();
        $allTypePermisison = PermissionType::where('name', 'all')->first();

        Schema::create('project_notes', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('project_id')->unsigned()->nullable();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade')->onUpdate('cascade');

            $table->string('title');

            $table->boolean('type')->default(0);

            $table->unsignedInteger('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->boolean('is_client_show')->default(0);

            $table->boolean('ask_password')->default(0);
            $table->string('details');

            $table->integer('added_by')->unsigned()->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');

            $table->integer('last_updated_by')->unsigned()->nullable();
            $table->foreign('last_updated_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');

            $table->timestamps();
        });

        Schema::create('project_user_notes', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->integer('project_note_id')->unsigned();
            $table->foreign('project_note_id')->references('id')->on('project_notes')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });

        $clientCustomPermisisons = [
            'add_project_note',
            'view_project_note',
            'edit_project_note',
            'delete_project_note',
        ];

        foreach ($clientCustomPermisisons as $permission) {
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
        Schema::dropIfExists('project_user_notes');
        Schema::dropIfExists('project_notes');
    }

}
