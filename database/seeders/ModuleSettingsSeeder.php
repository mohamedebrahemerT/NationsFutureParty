<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ModuleSetting;

class ModuleSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {

        // Employee Modules
        $module = new ModuleSetting();
        $module->type = 'employee';
        $module->module_name = 'dashboard';
        $module->status = 'active';
        $module->save();

        $module = new ModuleSetting();
        $module->type = 'employee';
        $module->module_name = 'projects';
        $module->status = 'active';
        $module->save();

        $module = new ModuleSetting();
        $module->type = 'employee';
        $module->module_name = 'task calendar';
        $module->status = 'active';
        $module->save();

        $module = new ModuleSetting();
        $module->type = 'employee';
        $module->module_name = 'messages';
        $module->status = 'active';
        $module->save();

        $module = new ModuleSetting();
        $module->type = 'employee';
        $module->module_name = 'sticky note';
        $module->status = 'active';
        $module->save();

        $module = new ModuleSetting();
        $module->type = 'employee';
        $module->module_name = 'notices';
        $module->status = 'active';
        $module->save();

        $module = new ModuleSetting();
        $module->type = 'employee';
        $module->module_name = 'leads';
        $module->status = 'active';
        $module->save();


        // Client Modules
        $module = new ModuleSetting();
        $module->type = 'client';
        $module->module_name = 'dashboard';
        $module->status = 'active';
        $module->save();

        $module = new ModuleSetting();
        $module->type = 'client';
        $module->module_name = 'invoices';
        $module->status = 'active';
        $module->save();

        $module = new ModuleSetting();
        $module->type = 'client';
        $module->module_name = 'projects';
        $module->status = 'active';
        $module->save();

        $module = new ModuleSetting();
        $module->type = 'client';
        $module->module_name = 'issues';
        $module->status = 'active';
        $module->save();

        $module = new ModuleSetting();
        $module->type = 'client';
        $module->module_name = 'sticky note';
        $module->status = 'active';
        $module->save();

    }

}
