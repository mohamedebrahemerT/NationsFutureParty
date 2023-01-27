<?php

use App\Models\Project;
use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

class AddDefaultCurrencyProjects extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $setting = Setting::first();

        if (!is_null($setting)) {
            Project::whereNull('currency_id')->update(
                [
                    'currency_id' => $setting->currency_id
                ]
            );
        }
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
