@extends('layouts.app')

@push('styles')
    <style>
        .table .thead-light th,
        .table tr td,
        .table h5 {
            font-size: 12px;
        }

    </style>
@endpush

@section('filter-section')
    <x-filters.filter-box>
        <div class="select-box d-flex py-2 pr-2 border-right-grey border-right-grey-sm-0">
            <p class="mb-0 pr-3 f-14 text-dark-grey d-flex align-items-center">@lang('app.employee')</p>
            <div class="select-status">
                <select class="form-control select-picker" name="user_id" id="user_id" data-live-search="true" data-size="8">
                    @if ($employees->count() > 1)
                        <option value="all">@lang('app.all')</option>
                    @endif
                    @forelse ($employees as $item)
                        <option @if (request('employee_id') == $item->id) selected @endif data-content="<div class='d-inline-block mr-1'><img class='taskEmployeeImg rounded-circle'
                                    src='{{ $item->image_url }}'></div> {{ ucfirst($item->name) }}"
                            value="{{ $item->id }}">{{ ucfirst($item->name) }}</option>
                    @empty
                        <option data-content="<div class='d-inline-block mr-1'><img class='taskEmployeeImg rounded-circle'
                                src='{{ user()->image_url }}'></div> {{ ucfirst(user()->name) }}"
                            value="{{ user()->id }}">{{ ucfirst(user()->name) }}</option>
                    @endforelse
                </select>
            </div>
        </div>

        <div class="select-box d-flex py-2 px-lg-2 px-md-2 px-0 border-right-grey border-right-grey-sm-0">
            <p class="mb-0 pr-3 f-14 text-dark-grey d-flex align-items-center">@lang('app.department')</p>
            <div class="select-status">
                <select class="form-control select-picker" name="department" id="department" data-live-search="true"
                    data-size="8">
                    <option value="all">@lang('app.all')</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}">{{ ucfirst($department->team_name) }}</option>
                    @endforeach
                </select>
            </div>
        </div>


        <div class="select-box d-flex py-2 px-lg-2 px-md-2 px-0 border-right-grey border-right-grey-sm-0">
            <p class="mb-0 pr-3 f-14 text-dark-grey d-flex align-items-center">@lang('app.month')</p>
            <div class="select-status">
                <select class="form-control select-picker" name="month" id="month" data-live-search="true" data-size="8">
                    <option @if ($month == '01') selected @endif value="01">
                        @lang('app.january')</option>
                    <option @if ($month == '02') selected @endif value="02">
                        @lang('app.february')</option>
                    <option @if ($month == '03') selected @endif value="03">
                        @lang('app.march')</option>
                    <option @if ($month == '04') selected @endif value="04">
                        @lang('app.april')</option>
                    <option @if ($month == '05') selected @endif value="05">
                        @lang('app.may')</option>
                    <option @if ($month == '06') selected @endif value="06">
                        @lang('app.june')</option>
                    <option @if ($month == '07') selected @endif value="07">
                        @lang('app.july')</option>
                    <option @if ($month == '08') selected @endif value="08">
                        @lang('app.august')</option>
                    <option @if ($month == '09') selected @endif value="09">
                        @lang('app.september')</option>
                    <option @if ($month == '10') selected @endif value="10">
                        @lang('app.october')</option>
                    <option @if ($month == '11') selected @endif value="11">
                        @lang('app.november')</option>
                    <option @if ($month == '12') selected @endif value="12">
                        @lang('app.december')</option>
                </select>
            </div>
        </div>

        <div class="select-box d-flex py-2 px-lg-2 px-md-2 px-0 border-right-grey border-right-grey-sm-0">
            <p class="mb-0 pr-3 f-14 text-dark-grey d-flex align-items-center">@lang('app.year')</p>
            <div class="select-status">
                <select class="form-control select-picker" name="year" id="year" data-live-search="true" data-size="8">
                    @for ($i = $year; $i >= $year - 4; $i--)
                        <option @if ($i == $year) selected @endif value="{{ $i }}">
                            {{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <!-- RESET START -->
        <div class="select-box d-flex py-1 px-lg-2 px-md-2 px-0">
            <x-forms.button-secondary class="btn-xs d-none" id="reset-filters" icon="times-circle">
                @lang('app.clearFilters')
            </x-forms.button-secondary>
        </div>
        <!-- RESET END -->

    </x-filters.filter-box>
@endsection

@section('content')
    <!-- CONTENT WRAPPER START -->
    <div class="content-wrapper px-4">

        <div class="d-flex">
            <div id="table-actions" class="flex-grow-1 align-items-center">
                <x-forms.link-primary :link="route('shifts.create')" class="mr-3 openRightModal float-left"
                icon="plus">
                    @lang('modules.attendance.bulkShiftAssign')
                </x-forms.link-primary>
                <x-forms.button-secondary id="export-all" class="mr-3 mb-2 mb-lg-0" icon="file-export">
                    @lang('app.exportExcel')
                </x-forms.button-secondary>
            </div>

            <div class="btn-group" role="group">
                <a href="{{ route('shifts.index') }}" class="btn btn-secondary f-14 btn-active" data-toggle="tooltip"
                    data-original-title="@lang('app.summary')"><i class="side-icon bi bi-list-ul"></i></a>

                <a href="{{ route('shifts-change.index') }}" class="btn btn-secondary f-14" data-toggle="tooltip"
                    data-original-title="@lang('modules.attendance.shiftChangeRequests')"><i
                        class="side-icon bi bi-hourglass-split"></i></a>

            </div>

        </div>

        <!-- Task Box Start -->
        <x-cards.data class="mt-3">
            <div class="row">
                <div class="col-md-12">
                    @foreach ($employeeShifts as $item)
                        <span class="badge badge-info f-12 p-1" style="background-color: {{ $item->color }}">
                            {{ $item->shift_short_code }} : {{ $item->shift_name }}</span>
                        {{ !$loop->last ? ' | ' : '' }}
                    @endforeach
                   | <i class="fa fa-star text-warning"></i> : @lang('app.menu.holiday')
                </div>
            </div>

            <div class="row">
                <div class="col-md-12" id="attendance-data"></div>
            </div>
        </x-cards.data>
        <!-- Task Box End -->
    </div>
    <!-- CONTENT WRAPPER END -->
@endsection

@push('scripts')
    <script>
        $('#user_id, #department, #month, #year').on('change', function() {
            if ($('#user_id').val() != "all") {
                $('#reset-filters').removeClass('d-none');
                showTable();
            } else if ($('#department').val() != "all") {
                $('#reset-filters').removeClass('d-none');
                showTable();
            } else if ($('#month').val() != "all") {
                $('#reset-filters').removeClass('d-none');
                showTable();
            } else if ($('#year').val() != "all") {
                $('#reset-filters').removeClass('d-none');
                showTable();
            } else {
                $('#reset-filters').addClass('d-none');
                showTable();
            }
        });

        $('#reset-filters').click(function() {
            $('#filter-form')[0].reset();
            $('.filter-box .select-picker').selectpicker("refresh");
            $('#reset-filters').addClass('d-none');
            showTable();
        });

        function showTable(loading = true) {

            var year = $('#year').val();
            var month = $('#month').val();

            var userId = $('#user_id').val();
            var department = $('#department').val();

            //refresh counts
            var url = "{{ route('shifts.index') }}";

            var token = "{{ csrf_token() }}";

            $.easyAjax({
                data: {
                    '_token': token,
                    year: year,
                    month: month,
                    department: department,
                    userId: userId
                },
                url: url,
                blockUI: loading,
                container: '.content-wrapper',
                success: function(response) {
                    $('#attendance-data').html(response.data);
                }
            });

        }

        $('#attendance-data').on('click', '.view-attendance', function() {
            var attendanceID = $(this).data('attendance-id');
            var url = "{{ route('attendances.show', ':attendanceID') }}";
            url = url.replace(':attendanceID', attendanceID);

            $(MODAL_XL + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_XL, url);
        });

        $('#attendance-data').on('click', '.change-shift', function(event) {
            var attendanceDate = $(this).data('attendance-date');
            var userData = $(this).closest('tr').children('td:first');
            var userID = $(this).data('user-id');
            var year = $('#year').val();
            var month = $('#month').val();

            var url = "{{ route('shifts.mark', [':userid', ':day', ':month', ':year']) }}";
            url = url.replace(':userid', userID);
            url = url.replace(':day', attendanceDate);
            url = url.replace(':month', month);
            url = url.replace(':year', year);

            $(MODAL_DEFAULT + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_DEFAULT, url);
        });

        showTable(false);

        $('#export-all').click(function() {
            var year = $('#year').val();
            var month = $('#month').val();
            var department = $('#department').val();
            var userId = $('#user_id').val();

            var url =
                "{{ route('shifts.export_all', [':year', ':month', ':userId', ':department']) }}";
            url = url.replace(':year', year).replace(':month', month).replace(':userId', userId).replace(':department', department);
            window.location.href = url;

        });
    </script>
@endpush
