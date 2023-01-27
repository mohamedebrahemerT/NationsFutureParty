<style>
    #colorpicker .form-group {
        width: 86%;
    }
    
    #colorpicker .input-group-text {
        height: 34px;
        margin-top: 44px;
    }
</style>

<link rel="stylesheet" href="{{ asset('vendor/css/bootstrap-colorpicker.css') }}" />

<div class="modal-header">
    <h5 class="modal-title" id="modelHeading">@lang('app.addNew') @lang('modules.lead.leadStatus')</h5>
    <button type="button"  class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">×</span>
    </button>
</div>

<div class="modal-body">
    <div class="portlet-body">
        <x-form id="createStatus" method="POST" class="ajax-form">
            <div class="form-body">
                <div class="row">
                    <div class="col-lg-6 col-md-12">
                        <x-forms.text fieldId="type" :fieldLabel="__('modules.lead.leadStatus')"
                            fieldName="type" fieldRequired="true" :fieldPlaceholder="__('placeholders.status')">
                        </x-forms.text>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div id="colorpicker" class="input-group">
                            <x-forms.text class="" :fieldLabel="__('modules.tasks.labelColor')" fieldName="label_color" fieldId="colorselector" fieldValue="#16813D" fieldRequired="true"/>
                            <span class="input-group-append">
                                <span class="input-group-text colorpicker-input-addon"><i></i></span>
                            </span>
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
    $('#colorpicker').colorpicker({"color": "#16813D"});

    // save status
    $('#save-status').click(function() {
        $.easyAjax({
            url: "{{ route('lead-status-settings.store') }}",
            container: '#createStatus',
            type: "POST",
            blockUI: true,
            disableButton: true,
            buttonSelector: "#save-status",
            data: $('#createStatus').serialize(),
            success: function(response) {
                if (response.status == "success") {
                    window.location.reload();
                }
            }
        })
    });
</script>
