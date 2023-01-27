<?php

namespace App\Observers;

use App\Http\Controllers\AppSettingController;
use App\Models\Setting;
use App\Models\User;

class SettingsObserver
{

    public function saving(Setting $setting)
    {

        session()->forget('global_setting');

        $user = user();

        if ($user) {
            $setting->last_updated_by = $user->id;
        }

        if ($setting->isDirty('date_format')) {

            if (isset(Setting::DATE_FORMATS[$setting->date_format])) {
                $setting->date_picker_format = Setting::DATE_FORMATS[$setting->date_format];
            }
            else {
                // Default date picker format
                $setting->date_picker_format = 'mm/dd/yyyy';
            }
        }

        if (!isRunningInConsoleOrSeeding() && $setting->isDirty('currency_id')) {
            $allClients = User::allClients();
            $clientsArray = $allClients->pluck('id')->toArray();
            
            $appSettings = new AppSettingController();
            $appSettings->deleteSessions($clientsArray);
        }
    }

}
