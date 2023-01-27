<?php

use App\Models\Module;
use App\Models\ModuleSetting;
use App\Models\Permission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveLeadFilePermission extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Permission::where('name', 'edit_lead_files')->delete();
        Permission::where('name', 'edit_task_files')->delete();

        Permission::where('name', 'add_lead_note')->update(['allowed_permissions' => '{"all":4, "none":5}']);
        Permission::where('name', 'change_lead_status')->update(['allowed_permissions' => '{"all":4, "none":5}']);

        ModuleSetting::create(['type' => 'employee', 'module_name' => 'orders']);

        Permission::where('name', 'view_knowledgebase')
            ->orWhere('name', 'edit_knowledgebase')
            ->orWhere('name', 'delete_knowledgebase')
            ->update([
                'allowed_permissions' => '{"all":4,"added":1,"none":5}'
            ]);
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
