<?php

namespace App\Console\Commands;

use App\Events\TaskReminderEvent;
use App\Models\Setting;
use App\Models\Task;
use App\Models\TaskboardColumn;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendTaskReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-task-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send task reminders';
    /**
     * @var \Illuminate\Contracts\Foundation\Application|\Illuminate\Session\SessionManager|\Illuminate\Session\Store|mixed
     */
    private $global_setting;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        try {
            $this->global_setting = global_setting();
        } catch (\Exception $e) {
            Log::info($e);
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function handle()
    {
        $now = Carbon::now($this->global_setting->timezone);
        $completedTaskColumn = TaskboardColumn::completeColumn();

        if ($this->global_setting->before_days > 0) {
            $beforeDeadline = $now->clone()->subDays($this->global_setting->before_days)->format('Y-m-d');
            $tasks = Task::select('id')->where('due_date', $beforeDeadline)->where('board_column_id', '<>', $completedTaskColumn->id)->get();

            foreach ($tasks as $key => $task) {
                event(new TaskReminderEvent($task));
            }
        }

        if ($this->global_setting->after_days > 0) {
            $afterDeadline = $now->clone()->addDays($this->global_setting->after_days)->format('Y-m-d');
            $tasks = Task::select('id')->where('due_date', $afterDeadline)->where('board_column_id', '<>', $completedTaskColumn->id)->get();

            foreach ($tasks as $key => $task) {
                event(new TaskReminderEvent($task));
            }
        }

        if ($this->global_setting->on_deadline) {
            $onDeadline = $now->clone()->format('Y-m-d');
            $tasks = Task::select('id')->where('due_date', $onDeadline)->where('board_column_id', '<>', $completedTaskColumn->id)->get();

            foreach ($tasks as $key => $task) {
                event(new TaskReminderEvent($task));
            }
        }

    }

}
