<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\CustomFieldGroup;

class CustomFieldGroupChangeTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $coustomGroups = CustomFieldGroup::all();

        foreach($coustomGroups as $group){
            if($group->name == 'Client'){
                $group->model = 'App\Models\ClientDetails';
            }

            if($group->name == 'Employee'){
                $group->model = 'App\Models\EmployeeDetails';
            }

            if($group->name == 'Project'){
                $group->model = 'App\Models\Project';
            }

            if($group->name == 'Invoice'){
                $group->model = 'App\Models\Invoice';
            }

            if($group->name == 'Estimate'){
                $group->model = 'App\Models\Estimate';
            }

            if($group->name == 'Task'){
                $group->model = 'App\Models\Task';
            }

            if($group->name == 'Lead'){
                $group->model = 'App\Models\Lead';
            }

            $group->save();
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
