<div class="modal-header">
    <h5 class="modal-title" id="modelHeading">@lang('modules.projects.createMilestone')</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">×</span></button>
</div>
<x-form id="addProjectMilestoneForm" method="PUT">
    <div class="modal-body">
        <input type="hidden" name="project_id" value="{{ $milestone->project_id }}">
        <input type="hidden" name="currency_id"
            value="{{ $milestone->currency_id ?? global_setting()->currency_id }}">
        <div class="row">
            <div class="col-md-4">
                <x-forms.text fieldId="milestone_title" :fieldLabel="__('modules.projects.milestoneTitle')"
                    :fieldValue="$milestone->milestone_title" fieldName="milestone_title" fieldRequired="true"
                    :fieldPlaceholder="__('placeholders.milestone')">
                </x-forms.text>
            </div>
            <div class="col-md-4">
                <x-forms.number fieldId="cost" :fieldLabel="__('modules.projects.milestoneCost')" fieldName="cost"
                    :fieldValue="$milestone->cost" :fieldPlaceholder="__('placeholders.price')" />
            </div>
            <div class="col-md-4">
                <x-forms.select fieldId="status" :fieldLabel="__('app.status')" fieldName="status">
                    <option @if ($milestone->status == 'incomplete') selected @endif value="incomplete">@lang('app.incomplete')</option>
                    <option @if ($milestone->status == 'complete') selected @endif value="complete">@lang('app.complete')</option>
                </x-forms.select>
            </div>

            <div class="col-md-12">
                <x-forms.textarea class="mr-0 mr-lg-2 mr-md-2" :fieldLabel="__('modules.projects.milestoneSummary')"
                    fieldName="summary" fieldRequired="true" fieldId="summary" :fieldValue="$milestone->summary"
                    :fieldPlaceholder="__('placeholders.milestoneSummary')">
                </x-forms.textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <x-forms.button-cancel data-dismiss="modal" class="border-0 mr-3">@lang('app.close')</x-forms.button-cancel>
        <x-forms.button-primary id="save-project-milestone" icon="check">@lang('app.save')</x-forms.button-primary>
    </div>
</x-form>

<script>
    $("#addProjectMilestoneForm .select-picker").selectpicker();

    $('#save-project-milestone').click(function() {
        var url = "{{ route('milestones.update', $milestone->id) }}";
        $.easyAjax({
            url: url,
            container: '#addProjectMilestoneForm',
            type: "POST",
            blockUI: true,
            disableButton: true,
            buttonSelector: '#save-project-milestone',
            data: $('#addProjectMilestoneForm').serialize(),
            success: function(response) {
                if (response.status == 'success') {
                    window.location.reload();
                }
            }
        })
    });
</script>
