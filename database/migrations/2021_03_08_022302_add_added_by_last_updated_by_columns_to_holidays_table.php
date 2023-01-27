<?php

use App\Models\Holiday;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddedByLastUpdatedByColumnsToHolidaysTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $admin = User::allAdmins()->first();

        Schema::table('holidays', function (Blueprint $table) {
            $table->integer('added_by')->unsigned()->nullable();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');

            $table->integer('last_updated_by')->unsigned()->nullable();
            $table->foreign('last_updated_by')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');
        });

        if (!is_null($admin)) {
            Holiday::whereNull('added_by')->update(['added_by' => $admin->id]);
            Holiday::whereNull('last_updated_by')->update(['last_updated_by' => $admin->id]);
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('holidays', function (Blueprint $table) {
            $table->dropForeign(['added_by']);
            $table->dropForeign(['last_updated_by']);

            $table->dropColumn(['added_by']);
            $table->dropColumn(['last_updated_by']);
        });
    }

}
