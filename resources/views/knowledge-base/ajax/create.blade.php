<div class="row">
    <div class="col-sm-12">
        <x-form id="save-knowledgebase-data-form">
            <div class="add-client bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    @lang('modules.knowledgeBase.knowledgeDetails')</h4>
                <div class="row p-20">
                    <div class="col-md-12">
                        <div class="form-group my-3">
                            <div class="d-flex">
                                <x-forms.radio fieldId="toEmployee"
                                    :fieldLabel="__('modules.knowledgeBase.toEmployee')" fieldName="to"
                                    fieldValue="employee" checked="true">
                                </x-forms.radio>
                                <x-forms.radio fieldId="toClient" :fieldLabel="__('modules.knowledgeBase.toClients')"
                                    fieldValue="client" fieldName="to"></x-forms.radio>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group my-3">
                            <x-forms.text fieldId="heading" :fieldLabel="__('modules.knowledgeBase.knowledgeHeading')"
                                fieldName="heading" fieldRequired="true"
                                :fieldPlaceholder="__('modules.knowledgeBase.knowledgeHeading')">
                            </x-forms.text>
                        </div>
                    </div>

                    <div class="col-md-6 knowledgecategory">
                        <div class="form-group my-3">
                            <x-forms.label fieldId="knowledgebasecategory" fieldRequired="true" :fieldLabel="__('modules.knowledgeBase.knowledgeCategory')">
                            </x-forms.label>

                            <x-forms.input-group >
                                <select class="form-control select-picker" name="category" id="category"
                                    data-live-search="true">
                                    <option value="">--</option>
                                    @foreach ($categories as $category)
                                        <option
                                        {{ isset($selected_category_id) && $selected_category_id == $category->id ? 'selected' : '' }}
                                         value="{{ $category->id }}">
                                            {{ ucwords($category->name) }}</option>
                                    @endforeach
                                </select>

                                <x-slot name="append">
                                    <button id="addKnowledgeCategory" type="button"
                                        class="btn btn-outline-secondary border-grey">@lang('app.add')</button>
                                </x-slot>

                            </x-forms.input-group>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <x-forms.label class="my-3"  fieldId="description-textt"
                                :fieldLabel="__('modules.knowledgeBase.knowledgeDesc')">
                            </x-forms.label>
                            <div id="description"></div>
                            <textarea name="description" id="description-text" class="d-none"></textarea>
                        </div>
                    </div>

                </div>

                <x-form-actions>
                    <x-forms.button-primary id="save-knowledgebase" class="mr-3" icon="check">@lang('app.save')
                    </x-forms.button-primary>
                    <x-forms.button-cancel :link="route('knowledgebase.index')" class="border-0">@lang('app.cancel')
                    </x-forms.button-cancel>
                </x-form-actions>
                
            </div>
        </x-form>

    </div>
</div>

<script>
    $(document).ready(function() {

        quillImageLoad('#description');

        // show/hide project detail
        $(document).on('change', 'input[type=radio][name=to]', function() {
            $('.department').toggleClass('d-none');
        });

        $('#save-knowledgebase').click(function() {
            const url = "{{ route('knowledgebase.store') }}";

            var note = document.getElementById('description').children[0].innerHTML;
            document.getElementById('description-text').value = note;

            $.easyAjax({
                url: url,
                container: '#save-knowledgebase-data-form',
                type: "POST",
                disableButton: true,
                blockUI: true,
                buttonSelector: "#save-knowledgebase",
                data: $('#save-knowledgebase-data-form').serialize(),
                success: function(response) {
                    if (response.status == 'success') {
                        if ($(MODAL_XL).hasClass('show')) {
                            $(MODAL_XL).hide();
                            window.location.reload();
                        } else {
                            window.location.href = response.redirectUrl;
                        }
                    }
                }
            });
        });

        $('#addKnowledgeCategory').click(function() {
            const url = "{{ route('knowledgebasecategory.create') }}";
            $(MODAL_LG + ' ' + MODAL_HEADING).html('...');
            $.ajaxModal(MODAL_LG, url);
        })

        init(RIGHT_MODAL);
    });
</script>
