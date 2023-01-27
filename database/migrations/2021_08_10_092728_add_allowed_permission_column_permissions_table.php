<?php

use App\Models\Permission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAllowedPermissionColumnPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->text('allowed_permissions')->nullable();
        });

        Permission::whereNull('allowed_permissions')->update(['allowed_permissions' => '{"all":4, "added":1, "owned":2,"both":3, "none":5}']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn(['allowed_permissions']);
        });
    }

}
