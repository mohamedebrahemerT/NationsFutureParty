    @extends('layouts.app')

    @push('styles')
        @if (!is_null($viewEventPermission) && $viewEventPermission != 'none')
            <link rel="stylesheet" href="{{ asset('vendor/full-calendar/main.min.css') }}">
        @endif
        <style>
            .h-200 {
                max-height: 340px;
                overflow-y: auto;
            }

            .dashboard-settings {
                width: 600px;
            }

            @media (max-width: 768px) {
                .dashboard-settings {
                    width: 300px;
                }
            }

        </style>
    @endpush

    @section('content')
        <!-- CONTENT WRAPPER START -->
        <div class="px-4 py-2 border-top-0 emp-dashboard">
            <!-- WELOCOME START -->
            <div class="d-lg-flex d-md-flex d-block py-4">
                <!-- WELOCOME NAME START -->
                <div class="">
                    <h4 class=" mb-0 f-21 text-capitalize font-weight-bold">@lang('app.welcome')
                        {{ $user->name }}</h4>
                </div>
                <!-- WELOCOME NAME END -->

                @if (in_array('attendance', user_modules()) && !isset($cannotLogin))
                    <!-- CLOCK IN CLOCK OUT START -->
                    <div
                        class="ml-auto d-flex clock-in-out mb-3 mb-lg-0 mb-md-0 m mt-4 mt-lg-0 mt-md-0 justify-content-between">
                        <p
                            class="mb-0 text-lg-right text-md-right f-18 font-weight-bold text-dark-grey d-grid align-items-center">
                            <input type="hidden" id="current-latitude" name="current_latitude">
                            <input type="hidden" id="current-longitude" name="current_longitude">
                            {{ now()->timezone(global_setting()->timezone)->format(global_setting()->time_format) }}
                            @if (!is_null($currentClockIn))
                                <span class="f-11 font-weight-normal text-lightest">
                                    @lang('app.clockInAt') -
                                    {{ $currentClockIn->clock_in_time->timezone(global_setting()->timezone)->format(global_setting()->time_format) }}
                                </span>
                            @endif
                        </p>

                        @if (is_null($currentClockIn) && is_null($checkTodayLeave))
                            <button type="button" class="btn-primary rounded f-15 ml-4" id="clock-in"><i
                                    class="icons icon-login mr-2"></i>@lang('modules.attendance.clock_in')</button>
                        @endif
                        @if (!is_null($currentClockIn) && is_null($currentClockIn->clock_out_time))
                            <button type="button" class="btn-danger rounded f-15 ml-4" id="clock-out"><i
                                    class="icons icon-login mr-2"></i>@lang('modules.attendance.clock_out')</button>
                        @endif

                    </div>
                    <!-- CLOCK IN CLOCK OUT END -->
                @endif
            </div>
            <!-- WELOCOME END -->
            <!-- EMPLOYEE DASHBOARD DETAIL START -->
            <div class="row emp-dash-detail">
                <!-- EMP DASHBOARD INFO NOTICES START -->
                <div class="col-xl-5 col-lg-12 col-md-12 e-d-info-notices">
                    <div class="row">
                        <!-- EMP DASHBOARD INFO START -->
                        <div class="col-md-12">
                            <div class="card border-0 b-shadow-4 mb-3 e-d-info">
                                <div class="card-horizontal align-items-center">
                                    <div class="card-img">
                                        <img class="" src=" {{ $user->image_url }}" alt="Card image">
                                    </div>
                                    <div class="card-body border-0 pl-0">
                                        <h4 class="card-title f-18 f-w-500 mb-0">{{ $user->name }}</h4>
                                        <p class="f-14 font-weight-normal text-dark-grey mb-2">
                                            {{ $user->employeeDetails->designation->name ?? '--' }}</p>
                                        <p class="card-text f-12 text-lightest"> @lang('app.employeeId') :
                                            {{ strtoupper($user->employeeDetails->employee_id) }}</p>
                                    </div>
                                </div>

                                <div class="card-footer bg-white border-top-grey py-3">
                                    <div class="d-flex flex-wrap justify-content-between">
                                        <span>
                                            <label class="f-12 text-dark-grey mb-12 text-capitalize" for="usr">
                                                @lang('app.open') @lang('app.menu.tasks') </label>
                                            <p class="mb-0 f-18 f-w-500">
                                                <a href="{{ route('tasks.index') . '?assignee=me' }}"
                                                    class="text-dark">
                                                    {{ $counts->totalPendingTasks }}
                                                </a>
                                            </p>
                                        </span>
                                        <span>
                                            <label class="f-12 text-dark-grey mb-12 text-capitalize" for="usr">
                                                @lang('app.menu.projects') </label>
                                            <p class="mb-0 f-18 f-w-500">
                                                <a href="{{ route('projects.index') . '?assignee=me&status=all' }}"
                                                    class="text-dark">{{ $totalProjects }}</a>
                                            </p>
                                        </span>
                                        <span>
                                            <label class="f-12 text-dark-grey mb-12 text-capitalize" for="usr">
                                                @lang('modules.dashboard.totalHoursLogged') </label>
                                            <p class="mb-0 f-18 f-w-500">
                                                <a href="{{ route('timelogs.index') . '?assignee=me&start=' . now()->format(global_setting()->date_format) . '&end=' . now()->format(global_setting()->date_format) }}"
                                                    class="text-dark">{{ intdiv($todayTotalHours, 60) }}
                                                </a>
                                            </p>
                                        </span>

                                        @if (isset($totalOpenTickets))
                                            <span>
                                                <label class="f-12 text-dark-grey mb-12 text-capitalize" for="usr">
                                                    @lang('modules.dashboard.totalOpenTickets') </label>
                                                <p class="mb-0 f-18 f-w-500">
                                                    <a href="{{ route('tickets.index') . '?agent=me&status=open' }}"
                                                        class="text-dark">{{ $totalOpenTickets }}</a>
                                                </p>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- EMP DASHBOARD INFO END -->

                        @if (in_array('attendance', user_modules()))
                            <div class="col-sm-12">
                                <x-cards.data class="mb-3" :title="__('modules.attendance.shiftSchedule')" padding="false" otherClasses="h-200">
                                    <x-slot name="action">
                                        <x-forms.button-primary id="view-shifts">@lang('modules.attendance.shift')
                                        </x-forms.button-primary>
                                    </x-slot>

                                    <x-table>
                                        @foreach ($currentWeekDates as $key => $weekDate)
                                            @if (isset($weekShifts[$key]))
                                                <tr>
                                                    <td class="pl-20">
                                                        {{ $weekDate->format(global_setting()->date_format) }}
                                                    </td>
                                                    <td>{{ $weekDate->format('l') }}</td>
                                                    <td>
                                                        @if (isset($weekShifts[$key]->shift))
                                                            <span class="badge badge-success"
                                                                style="background-color:{{ $weekShifts[$key]->shift->color }}">{{ $weekShifts[$key]->shift->shift_name }}</span>
                                                        @else
                                                            {!! $weekShifts[$key] !!}
                                                        @endif
                                                    </td>
                                                    @if (attendance_setting()->allow_shift_change)
                                                        <td class="pr-20 text-right">
                                                            @if (isset($weekShifts[$key]->shift))
                                                                <div class="task_view">
                                                                    <a href="javascript:;"
                                                                        data-shift-schedule-id="{{ $weekShifts[$key]->id }}"
                                                                        class="taskView border-right-0 request-shift-change f-11">@lang('modules.attendance.requestChange')</a>
                                                                </div>
                                                            @else
                                                                --
                                                            @endif
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endif
                                        @endforeach
                                    </x-table>
                                </x-cards.data>
                            </div>
                        @endif

                        <!-- EMP DASHBOARD BIRTHDAY START -->
                        <div class="col-sm-12">
                            <x-cards.data class="e-d-info mb-3" :title="__('modules.dashboard.birthday')" padding="false" otherClasses="h-200">
                                <x-table>
                                    @forelse ($upcomingBirthdays as $upcomingBirthday)
                                        <tr>
                                            <td class="pl-20">
                                                <x-employee :user="$upcomingBirthday->user" />
                                            </td>
                                            <td class="pr-20"><span class="badge badge-secondary p-2">
                                                    <i class="fa fa-birthday-cake"></i>
                                                    {{ $upcomingBirthday->date_of_birth->format('d') }}
                                                    {{ $upcomingBirthday->date_of_birth->format('M') }}</span></td>
                                            <td class="pr-20">
                                                @php
                                                    $currentYear = \Carbon\Carbon::now()->year;
                                                    $dateBirth = $upcomingBirthday->date_of_birth->format($currentYear . '-m-d');
                                                    $dateBirth = \Carbon\Carbon::parse($dateBirth);
                                                    $date1 = \Carbon\Carbon::now();
                                                    $date1 = strtotime($date1);
                                                    $date2 = strtotime($dateBirth);
                                                    $diff = $date2 - $date1;
                                                    $diff_in_days = floor($diff / (60 * 60 * 24)) + 1;
                                                @endphp
                                                @if ($diff_in_days == 0)
                                                    <span class="badge badge-light p-2">@lang('app.today')</span>
                                                @else
                                                    <span class="badge badge-light p-2">@lang('modules.dashboard.inDays',
                                                        ['days' => $diff_in_days])</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="shadow-none">
                                                <x-cards.no-record icon="birthday-cake" :message="__('messages.noRecordFound')" />
                                            </td>
                                        </tr>
                                    @endforelse
                                </x-table>
                            </x-cards.data>
                        </div>
                        <!-- EMP DASHBOARD BIRTHDAY END -->

                        @if (!is_null($myActiveTimer))
                            <div class="col-sm-12" id="myActiveTimerSection">
                                <x-cards.data :title="__('modules.timeLogs.myActiveTimer')">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            {{ $myActiveTimer->start_time->timezone(global_setting()->timezone)->format('M d, Y' . ' - ' . global_setting()->time_format) }}
                                            <p class="text-primary my-2">
                                                @php
                                                    $endTime = now();
                                                    $totalHours = $endTime->diff($myActiveTimer->start_time)->format('%d') * 24 + $endTime->diff($myActiveTimer->start_time)->format('%H');
                                                    $totalMinutes = $totalHours * 60 + $endTime->diff($myActiveTimer->start_time)->format('%i');
                                                    
                                                    $totalMinutes = $totalMinutes - $myActiveTimer->breaks->sum('total_minutes');
                                                    
                                                    $timeLog = intdiv($totalMinutes, 60) . ' ' . __('app.hrs') . ' ';
                                                    
                                                    if ($totalMinutes % 60 > 0) {
                                                        $timeLog .= $totalMinutes % 60 . ' ' . __('app.mins');
                                                    }
                                                @endphp

                                                <strong>@lang('modules.timeLogs.totalHours'):</strong> {{ $timeLog }}
                                            </p>

                                            <ul class="list-group">
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center f-12 text-dark-grey">
                                                    <span><i class="fa fa-clock"></i>
                                                        @lang('modules.timeLogs.startTime')</span>
                                                    {{ $myActiveTimer->start_time->timezone(global_setting()->timezone)->format(global_setting()->time_format) }}
                                                </li>
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center f-12 text-dark-grey">
                                                    <span><i class="fa fa-briefcase"></i> @lang('app.task')</span>
                                                    <a href="{{ route('tasks.show', $myActiveTimer->task->id) }}"
                                                        class="text-dark-grey openRightModal">{{ $myActiveTimer->task->heading }}</a>
                                                </li>
                                                @foreach ($myActiveTimer->breaks as $item)
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center f-12 text-dark-grey">
                                                        @if (!is_null($item->end_time))
                                                            @php
                                                                $endTime = $item->end_time;
                                                                $totalHours = $endTime->diff($item->start_time)->format('%d') * 24 + $endTime->diff($item->start_time)->format('%H');
                                                                $totalMinutes = $totalHours * 60 + $endTime->diff($item->start_time)->format('%i');
                                                                
                                                                $timeLog = intdiv($totalMinutes, 60) . ' ' . __('app.hrs') . ' ';
                                                                
                                                                if ($totalMinutes % 60 > 0) {
                                                                    $timeLog .= $totalMinutes % 60 . ' ' . __('app.mins');
                                                                }
                                                            @endphp
                                                            <span><i class="fa fa-mug-hot"></i>
                                                                @lang('modules.timeLogs.break')
                                                                ({{ $timeLog }})
                                                            </span>
                                                            {{ $item->start_time->timezone(global_setting()->timezone)->format(global_setting()->time_format) . ' - ' . $item->end_time->timezone(global_setting()->timezone)->format(global_setting()->time_format) }}
                                                        @else
                                                            <span><i class="fa fa-mug-hot"></i>
                                                                @lang('modules.timeLogs.break')</span>
                                                            {{ $item->start_time->timezone(global_setting()->timezone)->format(global_setting()->time_format) }}
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>

                                        </div>
                                        <div class="col-sm-12 pt-3 text-right">
                                            @if ($editTimelogPermission == 'all' || ($editTimelogPermission == 'added' && $myActiveTimer->added_by == user()->id) || ($editTimelogPermission == 'owned' && (($myActiveTimer->project && $myActiveTimer->project->client_id == user()->id) || $myActiveTimer->user_id == user()->id)) || ($editTimelogPermission == 'both' && (($myActiveTimer->project && $myActiveTimer->project->client_id == user()->id) || $myActiveTimer->user_id == user()->id || $myActiveTimer->added_by == user()->id)))
                                                @if (is_null($myActiveTimer->activeBreak))
                                                    <x-forms.button-secondary icon="pause-circle"
                                                        data-time-id="{{ $myActiveTimer->id }}" id="pause-timer-btn">
                                                        @lang('modules.timeLogs.pauseTimer')</x-forms.button-secondary>
                                                    <x-forms.button-primary class="ml-3 stop-active-timer"
                                                        data-time-id="{{ $myActiveTimer->id }}" icon="stop-circle">
                                                        @lang('modules.timeLogs.stopTimer')</x-forms.button-primary>
                                                @else
                                                    <x-forms.button-primary id="resume-timer-btn" icon="play-circle"
                                                        data-time-id="{{ $myActiveTimer->activeBreak->id }}">
                                                        @lang('modules.timeLogs.resumeTimer')</x-forms.button-primary>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </x-cards.data>
                            </div>
                        @endif

                        @isset($notices)
                            <!-- EMP DASHBOARD NOTICE START -->
                            <div class="col-md-12">
                                <div class="mb-3 b-shadow-4 rounded bg-white pb-2">
                                    <!-- NOTICE HEADING START -->
                                    <div class="d-flex align-items-center b-shadow-4 p-20">
                                        <p class="mb-0 f-18 f-w-500"> @lang('app.menu.notices') </p>
                                    </div>
                                    <!-- NOTICE HEADING END -->
                                    <!-- NOTICE DETAIL START -->
                                    <div class="b-shadow-4 cal-info scroll ps" data-menu-vertical="1" data-menu-scroll="1"
                                        data-menu-dropdown-timeout="500" id="empDashNotice" style="overflow: hidden;">


                                        @foreach ($notices as $notice)
                                            <div class="card border-0 b-shadow-4 p-20 rounded-0">
                                                <div class="card-horizontal">
                                                    <div class="card-header m-0 p-0 bg-white rounded">
                                                        <x-date-badge :month="$notice->created_at->format('M')" :date="$notice->created_at
                                                            ->timezone(global_setting()->timezone)
                                                            ->format('d')" />
                                                    </div>
                                                    <div class="card-body border-0 p-0 ml-3">
                                                        <h4 class="card-title f-14 font-weight-normal text-capitalize mb-0">
                                                            <a href="{{ route('notices.show', $notice->id) }}"
                                                                class="openRightModal text-darkest-grey">{{ $notice->heading }}</a>
                                                        </h4>
                                                    </div>
                                                </div>
                                            </div><!-- card end -->
                                        @endforeach


                                        <div class="ps__rail-x" style="left: 0px; top: 0px;">
                                            <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                                        </div>
                                        <div class="ps__rail-y" style="top: 0px; left: 0px;">
                                            <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div>
                                        </div>
                                    </div>
                                    <!-- NOTICE DETAIL END -->
                                </div>
                            </div>
                            <!-- EMP DASHBOARD NOTICE END -->
                        @endisset

                    </div>
                </div>
                <!-- EMP DASHBOARD INFO NOTICES END -->
                <!-- EMP DASHBOARD TASKS PROJECTS EVENTS START -->
                <div class="col-xl-7 col-lg-12 col-md-12 e-d-tasks-projects-events">
                    <!-- EMP DASHBOARD TASKS PROJECTS START -->
                    <div class="row mb-3 mt-xl-0 mt-lg-4 mt-md-4 mt-4">
                        <div class="col-md-6">
                            <div
                                class="bg-white p-20 rounded b-shadow-4 d-flex justify-content-between align-items-center mb-4 mb-md-0 mb-lg-0">
                                <div class="d-block text-capitalize">
                                    <h5 class="f-15 f-w-500 mb-20 text-darkest-grey">@lang('app.menu.tasks')</h5>
                                    <div class="d-flex">
                                        <a href="{{ route('tasks.index') . '?assignee=me' }}">
                                            <p class="mb-0 f-21 font-weight-bold text-blue d-grid mr-5">
                                                {{ $inProcessTasks }}<span class="f-12 font-weight-normal text-lightest">
                                                    @lang('app.pending') </span>
                                            </p>
                                        </a>
                                        <a href="{{ route('tasks.index') . '?assignee=me&overdue=yes' }}">
                                            <p class="mb-0 f-21 font-weight-bold text-red d-grid">{{ $dueTasks }}<span
                                                    class="f-12 font-weight-normal text-lightest">@lang('app.overdue')</span>
                                            </p>
                                        </a>
                                    </div>
                                </div>
                                <div class="d-block">
                                    <i class="fa fa-list text-lightest f-27"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div
                                class="bg-white p-20 rounded b-shadow-4 d-flex justify-content-between align-items-center mt-3 mt-lg-0 mt-md-0">
                                <div class="d-block text-capitalize">
                                    <h5 class="f-15 f-w-500 mb-20 text-darkest-grey"> @lang('app.menu.projects') </h5>
                                    <div class="d-flex">
                                        <a href="{{ route('projects.index') . '?assignee=me&status=in progress' }}">
                                            <p class="mb-0 f-21 font-weight-bold text-blue d-grid mr-5">
                                                {{ $totalProjects }}<span
                                                    class="f-12 font-weight-normal text-lightest">@lang('app.inProgress')</span>
                                            </p>
                                        </a>

                                        <a href="{{ route('projects.index') . '?assignee=me&status=overdue' }}">
                                            <p class="mb-0 f-21 font-weight-bold text-red d-grid">
                                                {{ $dueProjects }}<span
                                                    class="f-12 font-weight-normal text-lightest">@lang('app.overdue')</span>
                                            </p>
                                        </a>
                                    </div>
                                </div>
                                <div class="d-block">
                                    <i class="fa fa-layer-group text-lightest f-27"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- EMP DASHBOARD TASKS PROJECTS END -->

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card border-0 b-shadow-4 mb-3 e-d-info">
                                <x-cards.data :title="__('modules.tasks.myTask')" padding="false" otherClasses="h-200">
                                    <x-table>
                                        <x-slot name="thead">
                                            <th>@lang('app.task')#</th>
                                            <th>@lang('app.task')</th>
                                            <th>@lang('app.status')</th>
                                            <th class="text-right pr-20">@lang('app.dueDate')</th>
                                        </x-slot>

                                        @forelse ($pendingTasks as $task)
                                            <tr>
                                                <td class="pl-20">
                                                    #{{ $task->id }}
                                                </td>
                                                <td>
                                                    <div class="media align-items-center">
                                                        <div class="media-body">
                                                            <h5 class="f-12 mb-1 text-darkest-grey"><a
                                                                    href="{{ route('tasks.show', [$task->id]) }}"
                                                                    class="openRightModal">{{ ucfirst($task->heading) }}</a>
                                                            </h5>
                                                            <p class="mb-0">
                                                                @foreach ($task->labels as $label)
                                                                    <span class="badge badge-secondary mr-1"
                                                                        style="background-color: {{ $label->label_color }}">{{ $label->label_name }}</span>
                                                                @endforeach
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="pr-20">
                                                    <i class="fa fa-circle mr-1 text-yellow"
                                                        style="color: {{ $task->boardColumn->label_color }}"></i>
                                                    {{ $task->boardColumn->column_name }}
                                                </td>
                                                <td class="pr-20" align="right">
                                                    @if (is_null($task->due_date))
                                                        --
                                                    @elseif ($task->due_date->endOfDay()->isPast())
                                                        <span
                                                            class="text-danger">{{ $task->due_date->format(global_setting()->date_format) }}</span>
                                                    @elseif ($task->due_date->setTimezone(global_setting()->timezone)->isToday())
                                                        <span class="text-success">{{ __('app.today') }}</span>
                                                    @else
                                                        <span>{{ $task->due_date->format(global_setting()->date_format) }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="shadow-none">
                                                    <x-cards.no-record icon="task" :message="__('messages.noRecordFound')" />
                                                </td>
                                            </tr>
                                        @endforelse
                                    </x-table>
                                </x-cards.data>
                            </div>
                        </div>
                    </div>

                    <!-- EMP DASHBOARD EVENTS START -->
                    @if (!is_null($viewEventPermission) && $viewEventPermission != 'none')
                        <div class="row">
                            <div class="col-md-12">
                                <x-cards.data :title="__('app.menu.Events')">
                                    <div id="calendar"></div>
                                </x-cards.data>
                            </div>
                        </div>
                    @endif
                    <!-- EMP DASHBOARD EVENTS END -->
                </div>
                <!-- EMP DASHBOARD TASKS PROJECTS EVENTS END -->
            </div>
            <!-- EMPLOYEE DASHBOARD DETAIL END -->
        </div>
        <!-- CONTENT WRAPPER END -->
    @endsection

    @push('scripts')
        @if (!is_null($viewEventPermission) && $viewEventPermission != 'none')
            <script src="{{ asset('vendor/full-calendar/main.min.js') }}"></script>
            <script src="{{ asset('vendor/full-calendar/locales-all.min.js') }}"></script>
            <script>
                var initialLocaleCode = '{{ user()->locale }}';
                var calendarEl = document.getElementById('calendar');

                var calendar = new FullCalendar.Calendar(calendarEl, {
                    locale: initialLocaleCode,
                    timeZone: '{{ global_setting()->timezone }}',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                    },
                    navLinks: true, // can click day/week names to navigate views
                    selectable: false,
                    initialView: 'listWeek',
                    selectMirror: true,
                    select: function(arg) {
                        addEventModal(arg.start, arg.end, arg.allDay);
                        calendar.unselect()
                    },
                    eventClick: function(arg) {
                        getEventDetail(arg.event.id);
                    },
                    editable: false,
                    dayMaxEvents: true, // allow "more" link when too many events
                    events: {
                        url: "{{ route('events.index') }}",
                    },
                    eventDidMount: function(info) {
                        $(info.el).css('background-color', info.event.extendedProps.bg_color);
                        $(info.el).css('color', info.event.extendedProps.color);
                    },
                    eventTimeFormat: { // like '14:30:00'
                        hour: global_setting.time_format == 'H:i' ? '2-digit' : 'numeric',
                        minute: '2-digit',
                        meridiem: global_setting.time_format == 'H:i' ? false : true
                    }
                });

                calendar.render();

                // Task Detail show in sidebar
                var getEventDetail = function(id) {
                    openTaskDetail();
                    var url = "{{ route('events.show', ':id') }}";
                    url = url.replace(':id', id);

                    $.easyAjax({
                        url: url,
                        blockUI: true,
                        container: RIGHT_MODAL,
                        historyPush: true,
                        success: function(response) {
                            if (response.status == "success") {
                                $(RIGHT_MODAL_CONTENT).html(response.html);
                                $(RIGHT_MODAL_TITLE).html(response.title);
                            }
                        },
                        error: function(request, status, error) {
                            if (request.status == 403) {
                                $(RIGHT_MODAL_CONTENT).html(
                                    '<div class="align-content-between d-flex justify-content-center mt-105 f-21">403 | Permission Denied</div>'
                                );
                            } else if (request.status == 404) {
                                $(RIGHT_MODAL_CONTENT).html(
                                    '<div class="align-content-between d-flex justify-content-center mt-105 f-21">404 | Not Found</div>'
                                );
                            } else if (request.status == 500) {
                                $(RIGHT_MODAL_CONTENT).html(
                                    '<div class="align-content-between d-flex justify-content-center mt-105 f-21">500 | Something Went Wrong</div>'
                                );
                            }
                        }
                    });
                };
            </script>
        @endif

        <script>
            $('#clock-in').click(function() {
                const url = "{{ route('attendances.clock_in_modal') }}";
                $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
                $.ajaxModal(MODAL_LG, url);
            });

            $('.request-shift-change').click(function() {
                var id = $(this).data('shift-schedule-id');
                var url = "{{ route('shifts-change.edit', ':id') }}";
                url = url.replace(':id', id);

                $(MODAL_DEFAULT + ' ' + MODAL_HEADING).html('...');
                $.ajaxModal(MODAL_DEFAULT, url);
            });

            $('#view-shifts').click(function() {
                const url = "{{ route('employee-shifts.index') }}";
                $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
                $.ajaxModal(MODAL_LG, url);
            });

            /** clock timer start here */
            function currentTime() {
                let date = new Date();
                date = moment.tz(date, "{{ global_setting()->timezone }}");

                let hour = date.hour();
                let min = date.minutes();
                let sec = date.seconds();
                let midday = "AM";
                midday = (hour >= 12) ? "PM" : "AM";
                @if (global_setting()->time_format == 'h:i A')
                    hour = (hour == 0) ? 12 : ((hour > 12) ? (hour - 12) : hour); /* assigning hour in 12-hour format */
                @endif
                hour = updateTime(hour);
                min = updateTime(min);
                document.getElementById("clock").innerText = `${hour} : ${min} ${midday}`
                const time = setTimeout(function() {
                    currentTime()
                }, 1000);
            }

            /* appending 0 before time elements if less than 10 */
            function updateTime(timer) {
                if (timer < 10) {
                    return "0" + timer;
                } else {
                    return timer;
                }
            }

            @if (!is_null($currentClockIn))
                $('#clock-out').click(function() {

                    var token = "{{ csrf_token() }}";
                    var currentLatitude = document.getElementById("current-latitude").value;
                    var currentLongitude = document.getElementById("current-longitude").value;

                    $.easyAjax({
                        url: "{{ route('attendances.update_clock_in') }}",
                        type: "GET",
                        data: {
                            currentLatitude: currentLatitude,
                            currentLongitude: currentLongitude,
                            _token: token,
                            id: '{{ $currentClockIn->id }}'
                        },
                        success: function(response) {
                            if (response.status == 'success') {
                                window.location.reload();
                            }
                        }
                    });
                });
            @endif
        </script>
    @endpush
