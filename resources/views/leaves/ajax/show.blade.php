@php
$editLeavePermission = user()->permission('edit_leave');
$deleteLeavePermission = user()->permission('delete_leave');
@endphp

<div id="leave-detail-section">
    <div class="row">
        <div class="col-sm-12">
            <div class="card bg-white border-0 b-shadow-4">
                <div class="card-header bg-white  border-bottom-grey text-capitalize justify-content-between p-20">
                    <div class="row">
                        <div class="col-md-10 col-10">
                            <h3 class="heading-h1">@lang('app.menu.leaves') @lang('app.details')</h3>
                        </div>
                        <div class="col-md-2 col-2 text-right">
                            <div class="dropdown">

                                @if ($leave->status == 'pending')
                                    <button
                                        class="btn btn-lg f-14 px-2 py-1 text-dark-grey text-capitalize rounded  dropdown-toggle"
                                        type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-h"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right border-grey rounded b-shadow-4 p-0"
                                        aria-labelledby="dropdownMenuLink" tabindex="0">

                                            @if ($editLeavePermission == 'all'
                                            || ($editLeavePermission == 'added' && user()->id == $leave->added_by)
                                            || ($editLeavePermission == 'owned' && user()->id == $leave->user_id)
                                            || ($editLeavePermission == 'both' && (user()->id == $leave->user_id || user()->id == $leave->added_by)))
                                                <a class="dropdown-item openRightModal"
                                                data-redirect-url="{{ url()->previous() }}"
                                                href="{{ route('leaves.edit', $leave->id) }}">@lang('app.edit')</a>
                                            @endif

                                            @if ($deleteLeavePermission == 'all'
                                            || ($deleteLeavePermission == 'added' && user()->id == $leave->added_by)
                                            || ($deleteLeavePermission == 'owned' && user()->id == $leave->user_id)
                                            || ($deleteLeavePermission == 'both' && (user()->id == $leave->user_id || user()->id == $leave->added_by)))
                                                <a class="dropdown-item delete-leave">@lang('app.delete')</a>
                                            @endif

                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    @php
                        $leaveType = '<span class="badge badge-success" style="background-color:' . $leave->type->color . '">' . $leave->type->type_name . '</span>';

                        if ($leave->status == 'approved') {
                            $class = 'text-light-green';
                            $status = __('app.approved');
                        } elseif ($leave->status == 'pending') {
                            $class = 'text-yellow';
                            $status = __('app.pending');
                        } else {
                            $class = 'text-red';
                            $status = __('app.rejected');
                        }
                        $paidStatus = '<i class="fa fa-circle mr-1 ' . $class . ' f-10"></i> ' . $status;

                        $reject_reason = !is_null($leave->reject_reason) ? $leave->reject_reason : '--';

                    @endphp

                    <div class="col-12 px-0 pb-3 d-lg-flex d-md-flex d-block">
                        <p class="mb-0 text-lightest f-14 w-30 d-inline-block text-capitalize">
                            @lang('modules.leaves.applicantName')</p>
                        <p class="mb-0 text-dark-grey f-14">
                            <x-employee :user="$leave->user" />
                        </p>
                    </div>

                    <x-cards.data-row :label="__('app.date')" :value="$leave->leave_date->format(global_setting()->date_format)"
                        html="true" />
                    <x-cards.data-row :label="__('modules.leaves.leaveType')" :value="$leaveType" html="true" />
                    <x-cards.data-row :label="__('modules.leaves.reason')" :value="$leave->reason" html="true" />
                    <x-cards.data-row :label="__('app.status')" :value="$paidStatus" html="true" />

                    @if ($leave->status == 'rejected')
                        <x-cards.data-row :label="__('messages.reasonForLeaveRejection')" :value="$reject_reason"
                            html="true" />
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('body').on('click', '.delete-leave', function() {
        Swal.fire({
            title: "@lang('messages.sweetAlertTitle')",
            text: "@lang('messages.recoverRecord')",
            icon: 'warning',
            showCancelButton: true,
            focusConfirm: false,
            confirmButtonText: "@lang('messages.confirmDelete')",
            cancelButtonText: "@lang('app.cancel')",
            customClass: {
                confirmButton: 'btn btn-primary mr-3',
                cancelButton: 'btn btn-secondary'
            },
            showClass: {
                popup: 'swal2-noanimation',
                backdrop: 'swal2-noanimation'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                var url = "{{ route('leaves.destroy', $leave->id) }}";

                var token = "{{ csrf_token() }}";

                $.easyAjax({
                    type: 'POST',
                    url: url,
                    data: {
                        '_token': token,
                        '_method': 'DELETE'
                    },
                    success: function(response) {
                        if (response.status == "success") {
                            window.location.href = response.redirectUrl;
                        }
                    }
                });
            }
        });
    });
</script>
