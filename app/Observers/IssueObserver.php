<?php

namespace App\Observers;

use App\Events\NewIssueEvent;
use App\Models\Issue;
use App\Models\Notification;

class IssueObserver
{

    public function created(Issue $issue)
    {
        if (!isRunningInConsoleOrSeeding() ) {
            event(new NewIssueEvent($issue));
        }
    }

    public function deleting(Issue $issue)
    {
        $notifiData = ['App\Notifications\NewIssue'];

        Notification::whereIn('type', $notifiData)
            ->whereNull('read_at')
            ->where('data', 'like', '{"id":'.$issue->id.',%')
            ->delete();
    }

}
