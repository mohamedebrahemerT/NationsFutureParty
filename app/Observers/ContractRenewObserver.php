<?php

namespace App\Observers;

use App\Models\Contract;
use App\Events\NewContractEvent;
use App\Models\ContractRenew;
use App\Models\GoogleCalendarModule;
use App\Models\Notification;
use App\Models\User;
use App\Services\Google;

class ContractRenewObserver
{

    public function saving(ContractRenew $contractRenew)
    {
        if (!isRunningInConsoleOrSeeding()) {
            if (user()) {
                $contractRenew->last_updated_by = user()->id;
            }
        }
    }

    public function creating(ContractRenew $contractRenew)
    {
        if (user()) {
            $contractRenew->added_by = user()->id;
        }
    }

}
