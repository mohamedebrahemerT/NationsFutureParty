<link rel="stylesheet" href="{{ asset('vendor/css/bootstrap-colorpicker.css') }}" />

<style>
    #colorpicker .form-group {
        width: 86.5%;
    }
    #colorpicker .input-group-text {
        height: 34px;
        margin-top: 44px;
    }
</style>


<div class="modal-header">
    <h5 class="modal-title" id="modelHeading">@lang('app.edit') @lang('modules.lead.leadStatus')</h5>
    <button type="button"  class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">×</span>
    </button>
</div>

<div class="modal-body">
    <div class="portlet-body">
        <x-form id="editStatus" method="PUT" class="ajax-form">
            <div class="form-body">
                <div class="row">
                    <div class="col-sm-4 col-md-12">
                        <x-forms.text fieldId="type" :fieldLabel="__('modules.lead.leadStatus')"
                            fieldName="type" fieldRequired="true" :fieldPlaceholder="__('placeholders.status')" :fieldValue="$status->type">
                        </x-forms.text>
                    </div>
                    <div class="col-sm-4 col-md-12">
                        <div id="colorpicker" class="input-group">
                            <x-forms.text class="" :fieldLabel="__('modules.tasks.labelColor')" fieldName="label_color" fieldId="colorselector" :fieldValue="$status->label_color" fieldRequired="true"/>
                            <span class="input-group-append">
                                <span class="input-group-text colorpicker-input-addon"><i></i></span>
                            </span>
                        </div>
                    </div>

                    <div class="col-sm-4 col-md-6">
                        <div class="my-3">
                            <label for="user_id" id="agentLabel"> @lang('modules.tasks.position') </label>
                            <select class="form-control select-picker" id="priority" data-live-search="true"
                                name="priority">
                                @for($i=1; $i<= $maxPriority; $i++)
                                    <option @if($i == $status->priority) selected @endif>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                </div>
            </div>
        </x-form>
    </div>
</div>

<div class="modal-footer">
    <x-forms.button-cancel data-dismiss="modal" class="border-0 mr-3">@lang('app.close')</x-forms.button-cancel>
    <x-forms.button-primary id="save-status" icon="check">@lang('app.save')</x-forms.button-primary>
</div>

<script src="{{ asset('vendor/jquery/bootstrap-colorpicker.js') }}"></script>

<script>

    $('#colorpicker').colorpicker({"color": "{{ $status->label_color }}"});

    $(".select-picker").selectpicker();

    // save status
    $('#save-status').click(function() {
        $.easyAjax({
            url: "{{route('lead-status-settings.update', $status->id)}}",
            container: '#editStatus',
            type: "PUT",
            blockUI: true,
            disableButton: true,
            buttonSelector: "#save-status",
            data: $('#editStatus').serialize(),
            success: function(response) {
                if (response.status == "success") {
                    window.location.reload();
                }
            }
        })
    });

</script>
