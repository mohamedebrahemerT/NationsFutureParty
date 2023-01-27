<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddSalutationColumnOnUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('salutation', ['mr', 'mrs', 'miss', 'dr', 'sir', 'madam'])->after('gender')->nullable();
        });

        DB::statement("ALTER TABLE leads MODIFY COLUMN salutation ENUM('mr', 'mrs', 'miss', 'dr', 'sir', 'madem')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('salutation');
        });
    }

}
