<?php

use App\Models\AttendanceSetting;
use App\Models\ProjectTimeLog;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixTimelogTime extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $attendanceSetting = AttendanceSetting::first();
        $setting = Setting::first();

        $timelogs = ProjectTimeLog::where('end_time', 'like', '%' . $attendanceSetting->office_end_time . '%')->get();

        foreach ($timelogs as $timelog) {
            $endDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $timelog->start_time->format('Y-m-d').' '.$attendanceSetting->office_end_time);
            $endDateTime = Carbon::parse($endDateTime)->shiftTimezone($setting->timezone)->timestamp;
           
            $timelog->end_time = Carbon::createFromTimestamp($endDateTime);

            $timelog->total_hours = (int)$timelog->end_time->diff($timelog->start_time)->format('%d') * 24 + (int)$timelog->end_time->diff($timelog->start_time)->format('%H');
            $timelog->total_minutes = ((int)$timelog->total_hours * 60) + (int)($timelog->end_time->diff($timelog->start_time)->format('%i'));

            $timelog->save();
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

}
