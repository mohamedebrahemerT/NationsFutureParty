<?php

use App\Http\Controllers\RolePermissionController;
use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\PermissionType;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdMissingAdminPermissions extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $rolePerm = new RolePermissionController();
        $rolePerm->addMissingAdminPermission();
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
