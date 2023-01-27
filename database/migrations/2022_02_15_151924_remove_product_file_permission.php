<?php

use App\Models\ModuleSetting;
use App\Models\Permission;
use App\Models\Session;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveProductFilePermission extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Permission::where('name', 'view_product_files')->delete();
        Permission::where('name', 'add_product_files')->delete();
        Permission::where('name', 'edit_product_files')->delete();
        Permission::where('name', 'delete_product_files')->delete();

        ModuleSetting::where('type', 'client')->where('module_name', 'expenses')->delete();
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
