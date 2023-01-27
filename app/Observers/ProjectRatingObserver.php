<?php

namespace App\Observers;

use App\Events\RatingEvent;
use App\Models\Notification;
use App\Models\ProjectRating;

class ProjectRatingObserver
{

    public function created(ProjectRating $rating)
    {
        if (!isRunningInConsoleOrSeeding()) {
            // Send notification to user
            event(new RatingEvent($rating, 'add'));
        }
    }

    public function deleting(ProjectRating $rating)
    {
        if (!isRunningInConsoleOrSeeding()) {
            // Send notification to user
            event(new RatingEvent($rating, 'update'));

        }

        $notifiData = ['App\Notifications\RatingUpdate'];

        Notification::whereIn('type', $notifiData)
            ->whereNull('read_at')
            ->where('data', 'like', '{"id":'.$rating->id.',%')
            ->delete();
    }

}
