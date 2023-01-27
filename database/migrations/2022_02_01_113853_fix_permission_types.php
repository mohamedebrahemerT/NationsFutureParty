<?php

use App\Models\Permission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixPermissionTypes extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Permission::where('name', 'manage_client_category')->update(['allowed_permissions' => '{"all":4, "none":5}']);
        Permission::where('name', 'manage_client_subcategory')->update(['allowed_permissions' => '{"all":4, "none":5}']);
        Permission::where('name', 'view_client_contacts')->update(['allowed_permissions' => '{"all":4, "added":1, "none":5}']);
        Permission::where('name', 'view_client_note')->update(['allowed_permissions' => '{"all":4, "added":1, "none":5}']);
        Permission::where('name', 'edit_employees')->update(['allowed_permissions' => '{"all":4, "added":1, "owned":2,"both":3, "none":5}']);
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
