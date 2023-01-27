<?php

use App\Models\Notification;
use App\Models\SubTask;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class ChangeSubtaskInNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        $notifiData = ['App\Notifications\SubTaskCreated', 'App\Notifications\SubTaskCompleted'];

        $notifications = Notification::whereIn('type', $notifiData)
            ->whereNull('read_at')
            ->get();

        if($notifications) {
            foreach ($notifications as $value) {

                if($value->data)
                {
                    $dt = json_decode($value->data);

                    if($dt && isset($dt->id))
                    {
                        if(isset($dt->task_id)) {
                            $task = SubTask::where('task_id', $dt->id)->first();
                        }
                        else{
                            $task = SubTask::where('id', $dt->id)->first();
                        }

                        if(!is_null($task)){
                            $value->data = $task->toArray();
                        }
                        else{
                            $value->read_at = Carbon::now();
                        }

                        $value->save();
                    }
                }
                else{
                    $value->read_at = Carbon::now();
                    $value->save();
                }
            }
        }

    }

}
