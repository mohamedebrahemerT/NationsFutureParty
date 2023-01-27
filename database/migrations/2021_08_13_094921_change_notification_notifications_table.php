<?php

use App\Models\Notification;
use App\Models\Task;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class ChangeNotificationNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        $notifiData = ['App\Notifications\NewTask', 'App\Notifications\TaskUpdated', 'App\Notifications\TaskComment',
        'App\Notifications\TaskCommentClient', 'App\Notifications\TaskCompleted', 'App\Notifications\NewClientTask'];

        $notifications = Notification::whereIn('type', $notifiData)
            ->whereNull('read_at')
            ->get();

        if($notifications){
            foreach ($notifications as $key => $value) {
                if($value->data) {
                    $dt = json_decode($value->data);

                    if($dt && isset($dt->id))
                    {
                        $task = Task::find($dt->id);

                        if($task) {
                            $value->data = $task->toArray();
                        }
                        else{
                            $value->read_at = Carbon::now();
                        }

                        $value->save();
                    }
                }
            }
        }
    }

}
