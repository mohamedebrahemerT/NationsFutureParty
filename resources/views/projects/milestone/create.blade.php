<div class="modal-header">
    <h5 class="modal-title" id="modelHeading">@lang('modules.projects.createMilestone')</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">×</span></button>
</div>
<x-form id="addProjectMilestoneForm">
    <div class="modal-body">
        <input type="hidden" name="project_id" value="{{ $project->id }}">
        <input type="hidden" name="currency_id" value="{{ $project->currency_id ?? global_setting()->currency_id }}">
        <div class="row">
            <div class="col-md-6">
                <x-forms.text fieldId="milestone_title" :fieldLabel="__('modules.projects.milestoneTitle')"
                    fieldName="milestone_title" fieldRequired="true" :fieldPlaceholder="__('placeholders.milestone')">
                </x-forms.text>
            </div>
            <div class="col-md-6">
                <x-forms.number fieldId="cost" :fieldLabel="__('modules.projects.milestoneCost')" fieldName="cost"
                    :fieldPlaceholder="__('placeholders.price')">
                    </x-forms.text>
            </div>
            <div class="col-md-6">
                <x-forms.select fieldId="status" :fieldLabel="__('app.status')" fieldName="status">
                    <option value="incomplete">@lang('app.incomplete')</option>
                    <option value="complete">@lang('app.complete')</option>
                </x-forms.select>
            </div>
            <div class="col-md-6">
                <x-forms.select fieldId="add_to_budget" :fieldLabel="__('modules.projects.addCostProjectBudget')"
                    fieldName="add_to_budget">
                    <option value="no">@lang('app.no')</option>
                    <option value="yes">@lang('app.yes')</option>
                </x-forms.select>
            </div>
            <div class="col-md-12">
                <x-forms.textarea class="mr-0 mr-lg-2 mr-md-2" :fieldLabel="__('modules.projects.milestoneSummary')"
                    fieldName="summary" fieldRequired="true" fieldId="summary"
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
        var url = "{{ route('milestones.store') }}";
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
