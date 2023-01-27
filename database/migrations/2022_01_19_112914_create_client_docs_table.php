<?php

use App\Models\Permission;
use App\Models\PermissionType;
use App\Models\RoleUser;
use App\Models\UserPermission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('client_docs', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('name', 200);
            $table->string('filename', 200);
            $table->string('hashname', 200);
            $table->string('size', 200)->nullable();
            $table->integer('added_by')->unsigned()->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');
            $table->integer('last_updated_by')->unsigned()->nullable();
            $table->foreign('last_updated_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');
            $table->timestamps();
        });

        $admins = RoleUser::where('role_id', '1')->get();
        $allTypePermisison = PermissionType::where('name', 'all')->first();

        $clientCustomPermisisons = [
            'add_client_document' => '{"all":4, "none":5}',
            'view_client_document' => '{"all":4, "added":1, "none":5}',
            'edit_client_document' => '{"all":4, "added":1, "none":5}',
            'delete_client_document' => '{"all":4, "added":1, "none":5}'
        ];

        foreach ($clientCustomPermisisons as $key => $permission) {
            $perm = Permission::create([
                'name' => $key,
                'display_name' => ucwords(str_replace('_', ' ', $key)),
                'is_custom' => 1,
                'module_id' => 1,
                'allowed_permissions' => $permission
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
        Schema::dropIfExists('client_docs');
    }

}
