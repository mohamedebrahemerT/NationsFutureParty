@extends('layouts.app')

@push('styles')
    <style>
        .form_custom_label {
            justify-content: left;
        }

    </style>
@endpush

@section('content')

    <!-- SETTINGS START -->
    <div class="w-100 d-flex ">

        <x-setting-sidebar :activeMenu="$activeSettingMenu" />

        <x-setting-card>
            <x-slot name="header">
                <div class="s-b-n-header" id="tabs">
                    <h2 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                        @lang($pageTitle)</h2>
                </div>
            </x-slot>

            <div class="col-lg-12 col-md-12 ntfcn-tab-content-left w-100 p-4 ">
                <div class="row">

                    <div class="col-sm-12 mt-3">
                        <h4>@lang('modules.tasks.reminder')</h4>
                    </div>

                    <div class="col-lg-6">
                        <x-forms.number :fieldLabel="__('modules.tasks.preDeadlineReminder') . ' (' . __('app.days').')'"
                            fieldName="before_days" fieldId="before_days" :fieldValue="global_setting()->before_days" />
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group my-3">
                            <label class="f-14 text-dark-grey mb-12 w-100"
                                for="usr">@lang('modules.tasks.onDeadlineReminder')</label>
                            <div class="d-flex">
                                <x-forms.radio fieldId="deadline-yes" :fieldLabel="__('app.yes')" fieldValue="yes"
                                    fieldName="on_deadline" :checked="global_setting()->on_deadline == 'yes'">
                                </x-forms.radio>
                                <x-forms.radio fieldId="deadline-no" :fieldLabel="__('app.no')" fieldValue="no"
                                    fieldName="on_deadline" :checked="global_setting()->on_deadline == 'no'">
                                </x-forms.radio>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <x-forms.number :fieldLabel="__('modules.tasks.postDeadlineReminder') . ' (' . __('app.days').')'"
                            fieldName="after_days" fieldId="after_days" :fieldValue="global_setting()->after_days" />
                    </div>

                    <div class="col-lg-4">
                        <x-forms.select fieldId="default_task_status" :fieldLabel="__('app.status')"
                            fieldName="default_task_status">
                            @foreach ($taskboardColumns as $item)
                                <option @if ($item->id == global_setting()->default_task_status) selected @endif value="{{ $item->id }}">
                                    {{ $item->slug == 'completed' || $item->slug == 'incomplete' ? __('app.' . $item->slug) : $item->column_name }}</option>
                            @endforeach
                        </x-forms.select>
                    </div>

                    <div class="col-lg-4">
                        <x-forms.number :fieldLabel="__('modules.tasks.taskboardDefaultLength')" fieldName="taskboard_length"
                            fieldId="taskboard_length" :fieldValue="global_setting()->taskboard_length" />
                    </div>

                </div>
            </div>

            <x-slot name="action">
                <!-- Buttons Start -->
                <div class="w-100 border-top-grey">
                    <x-setting-form-actions>
                        <x-forms.button-primary id="save-form" class="mr-3" icon="check">@lang('app.save')
                        </x-forms.button-primary>

                        <x-forms.button-cancel :link="url()->previous()" class="border-0">@lang('app.cancel')
                        </x-forms.button-cancel>
                    </x-setting-form-actions>
                </div>
                <!-- Buttons End -->
            </x-slot>

        </x-setting-card>

    </div>
    <!-- SETTINGS END -->
@endsection

@push('scripts')
    <script>
        $('#save-form').click(function() {
            var data = ($('#editSettings').serialize()).replace("_method=PUT", "_method=POST");

            $.easyAjax({
                url: "{{ route('task-settings.store') }}",
                container: '#editSettings',
                blockUI: true,
                type: "POST",
                data: data
            })
        });
    </script>
@endpush
