<?php

use App\Models\Permission;
use Illuminate\Database\Migrations\Migration;

class AddLeaveChangePermission extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Permission::where('name', 'add_leave')->update(['allowed_permissions' => '{"all":4, "added":1, "none":5}']);

        DB::statement("ALTER TABLE users MODIFY COLUMN salutation ENUM('mr', 'mrs', 'miss', 'dr', 'sir', 'madam')");
        DB::statement("ALTER TABLE leads MODIFY COLUMN salutation ENUM('mr', 'mrs', 'miss', 'dr', 'sir', 'madam')");
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
