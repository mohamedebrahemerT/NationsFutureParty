@php
$addAttendancePermission = user()->permission('add_attendance');
@endphp
<div class="table-responsive">
    <x-table class="table-bordered mt-3 table-hover" headType="thead-light">
        <x-slot name="thead">
            <th class="px-2">@lang('app.employee')</th>
            @for ($i = 1; $i <= $daysInMonth; $i++)
            <th class="px-2">{{ $i }} <br> <span class="text-dark-grey">{{ $weekMap[\Carbon\Carbon::parse(\Carbon\Carbon::parse($i . '-' . $month . '-' . $year))->dayOfWeek] }}</span></th>
            @endfor
            <th class="text-right px-2">@lang('app.total')</th>
        </x-slot>

        @foreach ($employeeAttendence as $key => $attendance)
            @php
                $totalPresent = 0;
                $userId = explode('#', $key);
                $userId = $userId[0];
            @endphp
            <tr>
                <td class="w-25 px-2"> {!! end($attendance) !!} </td>
                @foreach ($attendance as $key2 => $day)
                    @if ($key2 + 1 <= count($attendance))
                        <td class="px-2">
                            @if ($day == 'Leave')
                                <span data-toggle="tooltip" data-original-title="@lang('modules.attendance.leave')"><i
                                        class="fa fa-plane-departure text-warning"></i></span>
                            @elseif ($day == 'Absent')
                                <a href="javascript:;" @if ($addAttendancePermission == 'all') class="edit-attendance" @endif data-user-id="{{ $userId }}"
                                    data-attendance-date="{{ $key2 }}"><i
                                        class="fa fa-times text-lightest"></i></a>
                            @elseif ($day == 'Holiday')
                                <a href="javascript:;" data-toggle="tooltip"
                                    data-original-title="{{ $holidayOccasions[$key2] }}"
                                    data-user-id="{{ $userId }}" data-attendance-date="{{ $key2 }}"><i
                                        class="fa fa-star text-warning"></i></a>
                            @else
                                @if ($day != '-')
                                    @php
                                        $totalPresent = $totalPresent + 1;
                                    @endphp
                                @endif

                                {!! $day !!}
                            @endif
                        </td>
                    @endif
                @endforeach
                <td class="text-dark f-w-500 text-right attendance-total px-2 w-100">
                    {!! $totalPresent . ' / <span class="text-lightest">' . (count($attendance) - 1) . '</span>' !!}</td>
            </tr>
        @endforeach
    </x-table>
</div>
