<?php

namespace App\Console\Commands;

use App\Events\AutoFollowUpReminderEvent;
use App\Models\LeadFollowUp;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendAutoFollowUpReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-auto-followup-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification of followup to employee or added by user';


    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function handle()
    {
        $followups = LeadFollowUp::with('lead', 'lead.leadAgent', 'lead.leadAgent.user')->where('next_follow_up_date', '>=', Carbon::now())->where('send_reminder', 'yes')->get();

        foreach ($followups as $followup) {

            $reminder_date = null;

            if($followup->remind_type == 'day'){
                $reminder_date = $followup->next_follow_up_date->subDays($followup->remind_time);
            }
            elseif ($followup->remind_type == 'hour') {
                $reminder_date = $followup->next_follow_up_date->subHours($followup->remind_time);
            }
            else {
                $reminder_date = $followup->next_follow_up_date->subMinutes($followup->remind_time);
            }

            if($reminder_date->format('Y-m-d H:i') == Carbon::now()->timezone(global_setting()->timezone)->format('Y-m-d H:i')){
                event(new AutoFollowUpReminderEvent(LeadFollowUp::find($followup->id)));
            }

        }

    }

}


