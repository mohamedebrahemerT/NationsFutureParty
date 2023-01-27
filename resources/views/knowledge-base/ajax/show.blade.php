@php
$editPermission = user()->permission('edit_knowledgebase');
$deletePermission = user()->permission('delete_knowledgebase');
@endphp
<div id="notice-detail-section">
    <div class="row">
        <div class="col-sm-12">
            <div class="card bg-white border-0 b-shadow-4">
                <div class="card-header bg-white  border-bottom-grey text-capitalize justify-content-between p-20">
                    <div class="row">
                        <div class="col-lg-10 col-10">
                            <h3 class="heading-h1 mb-3">@lang('modules.knowledgeBase.knowledgeDetails')</h3>
                        </div>
                        <div class="col-lg-2 col-2 text-right">

                                <div class="dropdown">
                                    <button
                                        class="btn btn-lg f-14 px-2 py-1 text-dark-grey text-capitalize rounded  dropdown-toggle"
                                        type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-h"></i>
                                    </button>

                                    <div class="dropdown-menu dropdown-menu-right border-grey rounded b-shadow-4 p-0"
                                        aria-labelledby="dropdownMenuLink" tabindex="0">
                                        @if ($editPermission == 'all' || ($editPermission == 'added' && $knowledge->added_by == user()->id))
                                            <a class="dropdown-item openRightModal"
                                                href="{{ route('knowledgebase.edit', $knowledge->id) }}">@lang('app.edit')</a>
                                        @endif
                                        @if ($deletePermission == 'all' || ($deletePermission == 'added' && $knowledge->added_by == user()->id))
                                            <a class="dropdown-item delete-notice">@lang('app.delete')</a>
                                        @endif
                                    </div>
                                </div>

                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <x-cards.data-row :label="__('modules.knowledgeBase.knowledgeHeading')" :value="$knowledge->heading" />
                    <x-cards.data-row :label="__('app.createdAt')"
                        :value="$knowledge->created_at->format(global_setting()->date_format)" />

                    <x-cards.data-row :label="__('app.to')" :value="__('app.'.$knowledge->to)" />

                    <x-cards.data-row :label="__('app.description')" :value="!empty($knowledge->description) ? $knowledge->description : '--'" html="true" />

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('body').on('click', '.delete-notice', function() {
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
                var url = "{{ route('knowledgebase.destroy', $knowledge->id) }}";

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
