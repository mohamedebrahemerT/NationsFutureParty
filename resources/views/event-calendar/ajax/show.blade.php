@php
$editPermission = user()->permission('edit_events');
$deletePermission = user()->permission('delete_events');
$attendeesIds = $event->attendee->pluck('user_id')->toArray();
@endphp
<div id="task-detail-section">
    <div class="row">
        <div class="col-sm-12">
            <div class="card bg-white border-0 b-shadow-4">
                <div class="card-header bg-white  border-bottom-grey text-capitalize justify-content-between p-20">
                    <div class="row">
                        <div class="col-md-10">
                            <h3 class="heading-h1 mb-3">{{ $event->event_name }}</h3>
                        </div>
                        <div class="col-md-2 text-right">
                            <div class="dropdown">
                                <button
                                    class="btn btn-lg f-14 px-2 py-1 text-dark-grey text-capitalize rounded  dropdown-toggle"
                                    type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-ellipsis-h"></i>
                                </button>

                                <div class="dropdown-menu dropdown-menu-right border-grey rounded b-shadow-4 p-0"
                                    aria-labelledby="dropdownMenuLink" tabindex="0">
                                    @if ($editPermission == 'all'
                                    || ($editPermission == 'added' && $event->added_by == user()->id)
                                    || ($editPermission == 'owned' && in_array(user()->id, $attendeesIds))
                                    || ($editPermission == 'both' && (in_array(user()->id, $attendeesIds) || $event->added_by == user()->id))
                                    )
                                        <a class="dropdown-item openRightModal"
                                            href="{{ route('events.edit', $event->id) }}">@lang('app.edit')
                                        </a>
                                    @endif

                                    @if (
                                    $deletePermission == 'all'
                                    || ($deletePermission == 'added' && $event->added_by == user()->id)
                                    || ($deletePermission == 'owned' && in_array(user()->id, $attendeesIds))
                                    || ($deletePermission == 'both' && (in_array(user()->id, $attendeesIds) || $event->added_by == user()->id))
                                    )
                                        <a
                                            class="dropdown-item delete-event">@lang('app.delete')</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <x-cards.data-row :label="__('modules.events.eventName')" :value="ucfirst($event->event_name)"
                        html="true" />

                    <div class="col-12 px-0 pb-3 d-flex">
                        <p class="mb-0 text-lightest f-14 w-30 d-inline-block text-capitalize">
                            @lang('modules.events.attendees')</p>
                        <p class="mb-0 text-dark-grey f-14">
                            @foreach ($event->attendee as $item)
                                <div class="taskEmployeeImg rounded-circle mr-1">
                                    <img data-toggle="tooltip" data-original-title="{{ ucwords($item->user->name) }}"
                                        src="{{ $item->user->image_url }}">
                                </div>
                            @endforeach
                        </p>
                    </div>

                    <x-cards.data-row :label="__('app.description')" :value="ucfirst($event->description)"
                        html="true" />
                    <x-cards.data-row :label="__('modules.events.startOn')"
                        :value="$event->start_date_time->format(global_setting()->date_format. ' - '.global_setting()->time_format)"
                        html="true" />
                    <x-cards.data-row :label="__('modules.events.endOn')"
                        :value="$event->end_date_time->format(global_setting()->date_format. ' - '.global_setting()->time_format)"
                        html="true" />
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('body').on('click', '.delete-event', function() {
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
                var url = "{{ route('events.destroy', $event->id) }}";

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
