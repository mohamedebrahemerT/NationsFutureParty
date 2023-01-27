<?php

use App\Models\Permission;
use App\Models\PermissionType;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CraeteClientNotesTable extends Migration
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

        Schema::create('client_notes', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('client_id')->unsigned()->nullable();
            $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->string('title');

            $table->boolean('type')->default(0);

            $table->unsignedInteger('member_id')->nullable();
            $table->foreign('member_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->boolean('is_client_show')->default(0);

            $table->boolean('ask_password')->default(0);
            $table->string('details');

            $table->integer('added_by')->unsigned()->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');

            $table->integer('last_updated_by')->unsigned()->nullable();
            $table->foreign('last_updated_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');

            $table->timestamps();
        });

        Schema::create('client_user_notes', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->integer('client_note_id')->unsigned();
            $table->foreign('client_note_id')->references('id')->on('client_notes')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });

        $clientCustomPermisisons = [
            'add_client_note',
            'view_client_note',
            'edit_client_note',
            'delete_client_note',
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

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_user_notes');
        Schema::dropIfExists('client_notes');
    }

}
