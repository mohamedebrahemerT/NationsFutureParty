<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSidebarLogoStyleColumnGlobalSettings extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organisation_settings', function(Blueprint $table) {
            $table->enum('sidebar_logo_style', ['square', 'full'])->default('square');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organisation_settings', function(Blueprint $table) {
            $table->dropColumn(['sidebar_logo_style']);
        });
    }

}
