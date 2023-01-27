<?php

use App\Models\Permission;
use Illuminate\Database\Migrations\Migration;

class UpdateNoticePermissions extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Permission::whereIn('name', ['view_notice', 'edit_notice', 'delete_notice'])->update(['allowed_permissions' => '{"all":4, "added":1, "owned":2,"both":3, "none":5}']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

}
