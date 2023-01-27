@extends('layouts.app')

@push('datatable-styles')
    @include('sections.datatable_css')
@endpush

@php
$viewEmployeeTasks = user()->permission('view_employee_tasks');
$viewEmployeeProjects = user()->permission('view_employee_projects');
$viewEmployeeTimelogs = user()->permission('view_employee_timelogs');
$manageEmergencyContact = user()->permission('manage_emergency_contact');
$manageRolePermissionSetting = user()->permission('manage_role_permission_setting');
$manageShiftPermission = user()->permission('manage_employee_shifts');
@endphp

@section('filter-section')
    <!-- FILTER START -->
    <!-- PROJECT HEADER START -->
    <div class="d-flex filter-box project-header bg-white">

        <div class="mobile-close-overlay w-100 h-100" id="close-client-overlay"></div>
        <div class="project-menu d-lg-flex" id="mob-client-detail">

            <a class="d-none close-it" href="javascript:;" id="close-client-detail">
                <i class="fa fa-times"></i>
            </a>

            <x-tab :href="route('employees.show', $employee->id)" :text="__('modules.employees.profile')"
                class="profile" />

            @if ($viewEmployeeProjects == 'all' && in_array('projects', user_modules()))
                <x-tab :href="route('employees.show', $employee->id).'?tab=projects'" :text="__('app.menu.projects')"
                    ajax="false" class="projects" />
            @endif

            @if ($viewEmployeeTasks == 'all' && in_array('tasks', user_modules()))
                <x-tab :href="route('employees.show', $employee->id).'?tab=tasks'" :text="__('app.menu.tasks')" ajax="false"
                    class="tasks" />
            @endif

            @if (in_array('leaves', user_modules()))
                <x-tab :href="route('employees.show', $employee->id).'?tab=leaves'" :text="__('app.menu.leaves')"
                    ajax="false" class="leaves" />

                <x-tab :href="route('employees.show', $employee->id).'?tab=leaves-quota'" :text="__('app.menu.leavesQuota')"
                    class="leaves-quota" />
            @endif

            @if ($viewEmployeeTimelogs == 'all')
                <x-tab :href="route('employees.show', $employee->id).'?tab=timelogs'" :text="__('app.menu.timeLogs')"
                    ajax="false" class="timelogs" />
            @endif

            <x-tab :href="route('employees.show', $employee->id).'?tab=documents'" :text="__('app.menu.documents')"
                class="documents" />

             @if ($manageEmergencyContact == 'all' || ($employee->id == user()->id))
                <x-tab :href="route('employees.show', $employee->id).'?tab=emergency-contacts'"
                :text="__('modules.emergencyContact.emergencyContact')" class="emergency-contacts" />
            @endif

            @if ($manageShiftPermission == 'all')
                <x-tab :href="route('employees.show', $employee->id).'?tab=shifts'"
                :text="__('app.menu.shiftRoster')" class="permissions" />
            @endif

            @if ($manageRolePermissionSetting == 'all')
                <x-tab :href="route('employees.show', $employee->id).'?tab=permissions'"
                :text="__('modules.permission.permissions')" class="permissions" />
            @endif

        </div>

        <a class="mb-0 d-block d-lg-none text-dark-grey ml-auto mr-2 border-left-grey"
            onclick="openClientDetailSidebar()"><i class="fa fa-ellipsis-v "></i></a>

    </div>
    <!-- FILTER END -->
    <!-- PROJECT HEADER END -->

@endsection

@push('styles')
    <script src="{{ asset('vendor/jquery/frappe-charts.min.iife.js') }}"></script>
    <script src="{{ asset('vendor/jquery/Chart.min.js') }}"></script>
@endpush

@section('content')

    <div class="content-wrapper pt-0 border-top-0 client-detail-wrapper">
        @include($view)
    </div>

@endsection

@push('scripts')
    <script>
        $("body").on("click", ".project-menu .ajax-tab", function(event) {
            event.preventDefault();

            $('.project-menu .p-sub-menu').removeClass('active');
            $(this).addClass('active');

            const requestUrl = this.href;

            $.easyAjax({
                url: requestUrl,
                blockUI: true,
                container: ".content-wrapper",
                historyPush: true,
                blockUI: true,
                success: function(response) {
                    if (response.status == "success") {
                        $('.content-wrapper').html(response.html);
                        init('.content-wrapper');
                    }
                }
            });
        });
    </script>
    <script>
        const activeTab = "{{ $activeTab }}";
        $('.project-menu .' + activeTab).addClass('active');
    </script>
@endpush
