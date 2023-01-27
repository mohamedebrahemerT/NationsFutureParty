<?php

use App\Models\ModuleSetting;
use App\Models\Setting;
use App\Models\ThemeSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ResetThemeDefaultColors extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organisation_settings', function (Blueprint $table) {
            $table->enum('auth_theme', ['dark', 'light'])->default('light');
            $table->string('light_logo')->nullable();
        });

        Schema::table('theme_settings', function (Blueprint $table) {
            $table->enum('sidebar_theme', ['dark', 'light'])->default('dark')->after('user_css');
        });

        ThemeSetting::whereNotNull('id')->update([
            'header_color' => '#1d82f5',
            'sidebar_color' => '#171F29',
            'sidebar_text_color' => '#99A5B5',
            'link_color' => '#F7FAFF'
        ]);

        Setting::whereNotNull('id')->update(
            ['logo_background_color' => '#ffffff']
        );

        ModuleSetting::where('type', 'client')->where('module_name', 'product')->update(['module_name' => 'products']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('theme_settings', function (Blueprint $table) {
            $table->dropColumn(['sidebar_theme']);
        });

        Schema::table('organisation_settings', function (Blueprint $table) {
            $table->dropColumn(['auth_theme']);
            $table->dropColumn(['light_logo']);
        });
    }

}
