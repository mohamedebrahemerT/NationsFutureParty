<div class="modal-header">
    <h5 class="modal-title" id="modelHeading">@lang('modules.attendance.clock_in')</h5>
    <button type="button"  class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">×</span></button>
</div>
<div class="modal-body">
    <x-form id="startTimerForm">
        <div class="row justify-content-between">
            <div class="col" id="task_div">
                <h4 class="mb-4"><i class="fa fa-clock"></i> {{ now()->timezone(global_setting()->timezone)->format(global_setting()->date_format . ' ' . global_setting()->time_format) }}</h4>
                <div class="row">
                    <div class="col-md-6">
                        <x-forms.select fieldId="location" :fieldLabel="__('app.location')" fieldName="location"
                        search="true">
                            @foreach ($location as $locations)
                                <option @if ($locations->is_default == 1) selected @endif value="{{ $locations->id }}">
                                    {{ ucwords($locations->location) }}</option>
                            @endforeach
                        </x-forms.select>
                    </div>
                    <div class="col-md-6">
                        <x-forms.text fieldId="working_from" :fieldLabel="__('modules.attendance.working_from')"
                        fieldName="working_from" :fieldPlaceholder="__('placeholders.attendance.workFrom')"
                        fieldRequired="true">
                    </x-forms.text>
                    </div>
                </div>
            </div>
        </div>
    </x-form>
</div>
<div class="modal-footer">
    <x-forms.button-cancel data-dismiss="modal" class="border-0 mr-3">@lang('app.cancel')</x-forms.button-cancel>
    <x-forms.button-primary id="save-clock-in">@lang('modules.attendance.clock_in')</x-forms.button-primary>
</div>

@if ($attendanceSettings->radius_check == 'yes' || $attendanceSettings->save_current_location)
    <script>
        var currentLatitude = document.getElementById("current-latitude");
        var currentLongitude = document.getElementById("current-longitude");
        var x = document.getElementById("current-latitude");

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                // x.innerHTML = "Geolocation is not supported by this browser.";
            }
        }

        function showPosition(position) {
            currentLatitude.value = position.coords.latitude;
            currentLongitude.value = position.coords.longitude;
        }
        getLocation();

    </script>
@endif

<script>
    $('.select-picker').selectpicker();

    $('#save-clock-in').click(function() {
        var workingFrom = $('#working_from').val();
        var location = $('#location').val();

        var currentLatitude = document.getElementById("current-latitude").value;
        var currentLongitude = document.getElementById("current-longitude").value;

        var token = "{{ csrf_token() }}";

        $.easyAjax({
            url: "{{ route('attendances.store_clock_in') }}",
            type: "POST",
            buttonSelector: "#save-clock-in",
            disableButton: true,
            blockUI: true,
            container: '#startTimerForm',
            data: {
                working_from: workingFrom,
                location: location,
                currentLatitude: currentLatitude,
                currentLongitude: currentLongitude,
                _token: token
            },
            success: function(response) {
                if (response.status == 'success') {
                    window.location.reload();
                }
            }
        })
    })

</script>
