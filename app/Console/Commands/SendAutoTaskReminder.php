<?php

namespace App\Console\Commands;

use App\Events\AutoTaskReminderEvent;
use App\Models\Setting;
use App\Models\Task;
use App\Models\TaskboardColumn;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendAutoTaskReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-auto-task-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send task reminders';
    /**
     * @var Setting|\Illuminate\Database\Eloquent\Model|object|null
     */
    private $global_setting;


    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function handle()
    {
        try {
            $this->global_setting = Setting::first();
        }
        catch (\Exception $e) {
            // Catch exception here
            Log::info($e);
        }

        $now = Carbon::now($this->global_setting->timezone);
        $completedTaskColumn = TaskboardColumn::completeColumn();

        if ($this->global_setting->before_days > 0) {
            $beforeDeadline = $now->clone()->subDays($this->global_setting->before_days)->format('Y-m-d');
            $tasks = Task::select('id')->where('due_date', $beforeDeadline)->where('board_column_id', '<>', $completedTaskColumn->id)->get();

            foreach ($tasks as $key => $task) {
                event(new AutoTaskReminderEvent($task));
            }
        }

        if ($this->global_setting->after_days > 0) {
            $now->clone()->addDays($this->global_setting->after_days)->format('Y-m-d');
        }
    }

}
