<?php

use App\Models\Permission;
use App\Models\Task;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTicketPermissions extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Permission::where('name', 'add_tickets')->update(['allowed_permissions' => '{"all":4,"added":1, "none":5}']);

        // Fix missing assignee tasks

        $tasks = Task::whereDoesntHave('users')->whereNotNull('created_by')->get();

        foreach ($tasks as $key => $task) {
            $task->users()->sync($task->created_by);
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
