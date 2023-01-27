<?php

namespace App\Traits;

use App\Helper\Reply;
use App\Http\Requests\ClockIn\ClockInRequest;
use App\Models\Attendance;
use App\Models\AttendanceSetting;
use App\Models\CompanyAddress;
use App\Models\EmployeeDetails;
use App\Models\EmployeeShiftSchedule;
use App\Models\Event;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\Notice;
use App\Models\Project;
use App\Models\ProjectTimeLog;
use App\Models\Task;
use App\Models\TaskboardColumn;
use App\Models\Ticket;
use App\Models\TicketAgentGroups;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 *
 */
trait EmployeeDashboard
{

    /**
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function employeeDashboard()
    {

        $completedTaskColumn = TaskboardColumn::completeColumn();
        $attendanceSettings = EmployeeShiftSchedule::with('shift')->where('user_id', user()->id)->where('date', now(global_setting()->timezone)->toDateString())->first();

        if ($attendanceSettings) {
            $this->attendanceSettings = $attendanceSettings->shift;

        } else {
            $this->attendanceSettings = AttendanceSetting::first()->shift; // Do not get this from session here
        }

        
        $timestamp = now()->format('Y-m-d') . ' ' . $this->attendanceSettings->office_end_time;
        $officeEndTime = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, $this->global->timezone);
        $officeEndTime = $officeEndTime->setTimezone('UTC');

        if (now()->gt($officeEndTime)) {
            $this->cannotLogin = true;
        }

        $this->viewEventPermission = user()->permission('view_events');
        $this->viewNoticePermission = user()->permission('view_notice');
        $this->editTimelogPermission = user()->permission('edit_timelogs');

        // Getting Attendance setting data

        if (request('start') && request('end') && !is_null($this->viewEventPermission) && $this->viewEventPermission != 'none') {
            $eventData = array();

            $events = Event::with('attendee', 'attendee.user');

            if ($this->viewEventPermission == 'added') {
                $events->where('events.added_by', $this->user->id);
            }
            elseif ($this->viewEventPermission == 'owned' || $this->viewEventPermission == 'both') {
                $events->where('events.added_by', $this->user->id)
                    ->orWhere(function ($q) {
                        $q->whereHas('attendee.user', function ($query) {
                            $query->where('user_id', $this->user->id);
                        });
                    });
            }

            $events = $events->get();

            foreach ($events as $key => $event) {
                $eventData[] = [
                    'id' => $event->id,
                    'title' => ucfirst($event->event_name),
                    'start' => $event->start_date_time,
                    'end' => $event->end_date_time,
                    'extendedProps' => ['bg_color' => $event->label_color, 'color' => '#fff'],
                ];
            }

            return $eventData;
        }

        $this->totalProjects = Project::select('projects.id')
            ->join('project_members', 'project_members.project_id', '=', 'projects.id');
        $this->totalProjects = $this->totalProjects->where('project_members.user_id', '=', $this->user->id);

        $this->totalProjects = $this->totalProjects->groupBy('projects.id');
        $this->totalProjects = count($this->totalProjects->get());

        $this->counts = DB::table('users')
            ->select(
                DB::raw('(select IFNULL(sum(project_time_logs.total_minutes),0) from `project_time_logs` where user_id = ' . $this->user->id . ') as totalHoursLogged '),
                DB::raw('(select count(tasks.id) from `tasks` inner join task_users on task_users.task_id=tasks.id where tasks.board_column_id=' . $completedTaskColumn->id . ' and task_users.user_id = ' . $this->user->id . ') as totalCompletedTasks'),
                DB::raw('(select count(tasks.id) from `tasks` inner join task_users on task_users.task_id=tasks.id where tasks.board_column_id!=' . $completedTaskColumn->id . ' and task_users.user_id = ' . $this->user->id . ') as totalPendingTasks')
            )
            ->first();

        if (!is_null($this->viewNoticePermission) && $this->viewNoticePermission != 'none') {
            if ($this->viewNoticePermission == 'added') {
                $this->notices = Notice::latest()->where('added_by', $this->user->id)
                    ->limit(10)
                    ->get();
            }
            elseif ($this->viewNoticePermission == 'owned') {
                $this->notices = Notice::latest()
                    ->where(['to' => 'employee', 'department_id' => null])
                    ->orWhere(['department_id' => $this->user->employeeDetails->department_id])
                    ->limit(10)
                    ->get();
            }
            elseif ($this->viewNoticePermission == 'both') {
                $this->notices = Notice::latest()
                    ->where('added_by', $this->user->id)
                    ->orWhere(function ($q) {
                        $q->where(['to' => 'employee', 'department_id' => null])
                            ->orWhere(['department_id' => $this->user->employeeDetails->department_id]);
                    })
                    ->limit(10)
                    ->get();
            }
            elseif ($this->viewNoticePermission == 'all') {
                $this->notices = Notice::latest()
                    ->limit(10)
                    ->get();
            }
        }

        $checkTicketAgent = TicketAgentGroups::where('agent_id', user()->id)->first();

        if (!is_null($checkTicketAgent)) {
            $this->totalOpenTickets = Ticket::with('agent')->whereHas('agent', function ($q) {
                $q->where('id', user()->id);
            })->where('status', 'open')->count();
        }

        $tasks = $this->pendingTasks = Task::with('project', 'boardColumn', 'labels')
            ->join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->where('task_users.user_id', $this->user->id)
            ->where('tasks.board_column_id', '<>', $completedTaskColumn->id)
            ->select('tasks.*')
            ->groupBy('tasks.id')
            ->orderBy('tasks.id', 'desc')
            ->get();

        $this->inProcessTasks = $tasks->count();

        $this->dueTasks = Task::with('project')
            ->join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->where('task_users.user_id', $this->user->id)
            ->where('tasks.board_column_id', '<>', $completedTaskColumn->id)
            ->select('tasks.*')
            ->groupBy('tasks.id')
            ->where(DB::raw('DATE(tasks.`due_date`)'), '<', now(global_setting()->timezone)->toDateString())
            ->get()->count();

        $projects = Project::with('members')
            ->leftJoin('project_members', 'project_members.project_id', 'projects.id')
            ->leftJoin('users', 'project_members.user_id', 'users.id')
            ->selectRaw('projects.status, project_members.user_id, projects.deadline as due_date, projects.id')
            ->where('project_members.user_id', $this->user->id)
            ->where('projects.status', '<>', 'finished')
            ->where('projects.status', '<>', 'canceled')
            ->groupBy('projects.id')
            ->get();

        $projects = $projects->whereNotNull('projects.deadline');

        $this->dueProjects = $projects->filter(function ($value, $key) {
            return now()->gt($value->due_date);
        })->count();

        // Getting Today's Total Check-ins
        $this->todayTotalHours = ProjectTimeLog::with('task', 'user')
            ->where('user_id', user()->id)
            ->where(DB::raw('DATE(project_time_logs.`start_time`)'), '=', now()->toDateString())
            ->sum('total_minutes');

        // Getting Current Clock-in if exist
        $this->currentClockIn = Attendance::where(DB::raw('DATE(clock_in_time)'), now()->format('Y-m-d'))
            ->where('user_id', $this->user->id)->whereNull('clock_out_time')->first();

        $currentDate = now(global_setting()->timezone)->format('Y-m-d');

        $this->checkTodayLeave = Leave::where('status', 'approved')
            ->where('leave_date', now(global_setting()->timezone))
            ->where('user_id', user()->id)
            ->first();

        // Check Holiday by date
        $this->checkTodayHoliday = Holiday::where('date', $currentDate)->first();
        $this->myActiveTimer = ProjectTimeLog::with('task', 'user', 'project', 'breaks', 'activeBreak')
            ->where('user_id', user()->id)
            ->whereNull('end_time')->first();

        $currentDay = carbon::now()->format('m-d');

        $this->upcomingBirthdays = EmployeeDetails::with('user')
            ->select('*', 'date_of_birth', DB::raw('MONTH(date_of_birth) months'))
            ->whereNotNull('date_of_birth')
            ->where(function ($query) use($currentDay){
                $query->whereRaw('DATE_FORMAT(`date_of_birth`, "%m-%d") >= "'.$currentDay.'"')->orderBy('date_of_birth');
            })
            ->limit('5')
            ->orderBy('months')
            ->get();

        $now = now(global_setting()->timezone);
        $this->weekStartDate = $now->copy()->startOfWeek();
        $this->weekEndDate = $now->copy()->endOfWeek();

        $startWeek = $this->weekStartDate->format('d');
        $endWeek = $this->weekEndDate->addDay()->format('d');

        $this->employeeShifts = EmployeeShiftSchedule::where('user_id', user()->id)
            ->whereBetween(DB::raw('DATE(`date`)'), [$this->weekStartDate->format('Y-m-d'), $this->weekEndDate->format('Y-m-d')])
            ->selectRaw('DATE_FORMAT(date, "%d") as dates')
            ->get();
        $this->employeeShiftDates = $this->employeeShifts->pluck('dates')->toArray();

        $currentWeekDates = [];
        $weekShifts = [];

        for($i = $startWeek; $i <= $endWeek; $i++) {
            $date = Carbon::parse($now->year .'-' . $now->month . '-' . substr(str_repeat(0, 2).$i, - 2));
            array_push($currentWeekDates, $date);

            $checkLeave = Leave::where('user_id', user()->id)->where('leave_date', $date->toDateString())->where('status', 'approved')->first();

            $checkHoliday = Holiday::where('date', $date->toDateString())->first();

            if (!is_null($checkHoliday)) {
                $leave = '<i class="fa fa-star text-warning"></i> '. $checkHoliday->occassion;
                array_push($weekShifts, $leave);

            } elseif (!is_null($checkLeave)) {
                $leave = __('app.onLeave') . ': <span class="badge badge-success" style="background-color:' . $checkLeave->type->color . '">' . $checkLeave->type->type_name . '</span>';
                array_push($weekShifts, $leave);

            } elseif (in_array($date->format('d'), $this->employeeShiftDates)) {
                $shiftSchedule = EmployeeShiftSchedule::where('user_id', user()->id)->whereDate('date', $date->toDateString())->first();
                $shift = '<span class="badge badge-success" style="background-color:' . $shiftSchedule->shift->color . '">' . $shiftSchedule->shift->shift_name . '</span>';
                array_push($weekShifts, $shiftSchedule);

            } else {
                array_push($weekShifts, '--');
            }

        }

        $this->currentWeekDates = $currentWeekDates;
        $this->weekShifts = $weekShifts;

        return view('dashboard.employee.index', $this->data);
    }

    public function clockInModal()
    {
        $this->attendanceSettings = attendance_setting();
        $this->location = CompanyAddress::all();
        return view('dashboard.employee.clock_in_modal', $this->data);
    }

    public function storeClockIn(ClockInRequest $request)
    {
        $now = Carbon::now();
        $clockInCount = Attendance::getTotalUserClockIn($now->format('Y-m-d'), $this->user->id);

        $attendanceSettings = EmployeeShiftSchedule::with('shift')->where('user_id', user()->id)->where('date', now(global_setting()->timezone)->toDateString())->first();

        if ($attendanceSettings){
            $this->attendanceSettings = $attendanceSettings->shift;

        } else {
            $this->attendanceSettings = AttendanceSetting::first()->shift; // Do not get this from session here
        }

        // Check user by ip
        if (attendance_setting()->ip_check == 'yes') {
            $ips = (array)json_decode(attendance_setting()->ip_address);

            if (!in_array($request->ip(), $ips)) {
                return Reply::error(__('messages.notAnAuthorisedDevice'));
            }
        }

        // Check user by location
        if (attendance_setting()->radius_check == 'yes') {
            $checkRadius  = $this->isWithinRadius($request);

            if (!$checkRadius) {
                return Reply::error(__('messages.notAnValidLocation'));
            }
        }

        // Check maximum attendance in a day
        if ($clockInCount < $this->attendanceSettings->clockin_in_day) {

            // Set TimeZone And Convert into timestamp
            $currentTimestamp = $now->setTimezone('UTC');
            $currentTimestamp = $currentTimestamp->timestamp;;

            // Set TimeZone And Convert into timestamp in halfday time
            if ($this->attendanceSettings->halfday_mark_time) {
                $halfDayTimestamp = $now->format('Y-m-d') . ' ' . $this->attendanceSettings->halfday_mark_time;
                $halfDayTimestamp = Carbon::createFromFormat('Y-m-d H:i:s', $halfDayTimestamp, $this->global->timezone);
                $halfDayTimestamp = $halfDayTimestamp->setTimezone('UTC');
                $halfDayTimestamp = $halfDayTimestamp->timestamp;
            }


            $timestamp = $now->format('Y-m-d') . ' ' . $this->attendanceSettings->office_start_time;
            $officeStartTime = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, $this->global->timezone);
            $officeStartTime = $officeStartTime->setTimezone('UTC');


            $lateTime = $officeStartTime->addMinutes($this->attendanceSettings->late_mark_duration);

            $checkTodayAttendance = Attendance::where('user_id', $this->user->id)
                ->where(DB::raw('DATE(attendances.clock_in_time)'), '=', $now->format('Y-m-d'))->first();

            $attendance = new Attendance();
            $attendance->user_id = $this->user->id;
            $attendance->clock_in_time = $now;
            $attendance->clock_in_ip = request()->ip();

            $attendance->working_from = $request->working_from;
            $attendance->location_id = $request->location;

            if ($now->gt($lateTime) && is_null($checkTodayAttendance)) {
                $attendance->late = 'yes';
            }

            $attendance->half_day = 'no'; // default halfday

            // Check day's first record and half day time
            if (
                !is_null($this->attendanceSettings->halfday_mark_time)
                && is_null($checkTodayAttendance)
                && isset($halfDayTimestamp)
                && ($currentTimestamp > $halfDayTimestamp)
                ) {
                $attendance->half_day = 'yes';
            }

            $currentLatitude = $request->currentLatitude;
            $currentLongitude = $request->currentLongitude;

            if ($currentLatitude != '' && $currentLongitude != '') {
                $attendance->latitude = $currentLatitude;
                $attendance->longitude = $currentLongitude;
            }

            $attendance->save();

            return Reply::successWithData(__('messages.attendanceSaveSuccess'), ['time' => $now->format('h:i A'), 'ip' => $attendance->clock_in_ip, 'working_from' => $attendance->working_from]);
        }

        return Reply::error(__('messages.maxColckIn'));
    }

    public function updateClockIn(Request $request)
    {
        $now = Carbon::now();
        $attendance = Attendance::findOrFail($request->id);
        $this->attendanceSettings = attendance_setting();

        if ($this->attendanceSettings->ip_check == 'yes') {
            $ips = (array)json_decode($this->attendanceSettings->ip_address);

            if (!in_array($request->ip(), $ips)) {
                return Reply::error(__('messages.notAnAuthorisedDevice'));
            }
        }

        if ($this->attendanceSettings->radius_check == 'yes') {
            $checkRadius  = $this->isWithinRadius($request);

            if (!$checkRadius) {
                return Reply::error(__('messages.notAnValidLocation'));
            }
        }

        $attendance->clock_out_time = $now;
        $attendance->clock_out_ip = request()->ip();
        $attendance->save();

        return Reply::success(__('messages.attendanceSaveSuccess'));
    }

    /**
     * Calculate distance between two geo coordinates using Haversine formula and then compare
     * it with $radius.
     *
     * If distance is less than the radius means two points are close enough hence return true.
     * Else return false.
     *
     * @param Request $request
     *
     * @return boolean
     */
    private function isWithinRadius($request)
    {
        $radius = $this->attendanceSettings->radius;
        $currentLatitude = $request->currentLatitude;
        $currentLongitude = $request->currentLongitude;

        $latFrom = deg2rad($this->global->latitude);
        $latTo = deg2rad($currentLatitude);

        $lonFrom = deg2rad($this->global->longitude);
        $lonTo = deg2rad($currentLongitude);

        $theta = $lonFrom - $lonTo;

        $dist = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($theta);
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $distance = $dist * 60 * 1.1515 * 1609.344;
        return $distance <= $radius;
    }

}
