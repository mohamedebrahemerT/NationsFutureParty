<?php

use Illuminate\Database\Migrations\Migration;

class AddProductsModule extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $moduleSetting = \App\Models\ModuleSetting::where('module_name', 'products')->first();

        if(!$moduleSetting){
            $module = new \App\Models\ModuleSetting();
            $module->module_name = 'products';
            $module->status = 'active';
            $module->save();
        }
    }

}
