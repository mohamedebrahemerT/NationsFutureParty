<?php

namespace App\Observers;

use App\Events\NewChatEvent;
use App\Events\NewMessage;
use App\Models\Notification;
use App\Models\UserChat;

class NewChatObserver
{

    public function created(UserChat $userChat)
    {
        if (!isRunningInConsoleOrSeeding() && pusher_settings()->status) {
            event(new NewChatEvent($userChat));
            event(new NewMessage($userChat));
        }
    }

    public function deleting(UserChat $userChat)
    {
        $notifiData = ['App\Notifications\NewChat'];

        Notification::whereIn('type', $notifiData)
            ->whereNull('read_at')
            ->where('data', 'like', '{"id":'.$userChat->id.',%')
            ->delete();
    }

}
