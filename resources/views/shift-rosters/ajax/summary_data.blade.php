<div class="table-responsive">
    <x-table class="table-bordered mt-3 table-hover" headType="thead-light">
        <x-slot name="thead">
            <th class="px-2">@lang('app.employee')</th>
            @for ($i = 1; $i <= $daysInMonth; $i++)
                <th class="px-2">{{ $i }} <br> <span class="text-dark-grey">{{ $weekMap[\Carbon\Carbon::parse(\Carbon\Carbon::parse($i . '-' . $month . '-' . $year))->dayOfWeek] }}</span></th>
            @endfor
        </x-slot>

        @foreach ($employeeAttendence as $key => $attendance)
            @php
                $userId = explode('#', $key);
                $userId = $userId[0];
            @endphp
            <tr>
                <td class="px-2"> {!! end($attendance) !!} </td>
                @foreach ($attendance as $key2 => $day)
                    @if ($key2 + 1 <= count($attendance))
                        <td class="px-2">
                            @if ($day == 'Leave')
                                <span data-toggle="tooltip" data-original-title="@lang('modules.attendance.leave')"><i
                                        class="fa fa-plane-departure text-warning"></i></span>
                            @elseif ($day == 'EMPTY')
                                <button type="button" class="change-shift badge badge-light f-10 p-1"  data-user-id="{{ $userId }}"
                                    data-attendance-date="{{ $key2 }}"><i class="fa fa-plus"></i></button>
                            @elseif ($day == 'Holiday')
                                <a href="javascript:;" data-toggle="tooltip" class="change-shift"
                                    data-original-title="{{ $holidayOccasions[$key2] }}"
                                    data-user-id="{{ $userId }}" data-attendance-date="{{ $key2 }}"><i
                                        class="fa fa-star text-warning"></i></a>
                            @else
                                {!! $day !!}
                            @endif
                        </td>
                    @endif
                @endforeach
            </tr>
        @endforeach
    </x-table>
</div>
