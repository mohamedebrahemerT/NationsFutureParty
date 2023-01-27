<?php

namespace App\Observers;

use App\Events\EmployeeShiftScheduleEvent;
use App\Models\EmployeeShiftSchedule;

class EmployeeShiftScheduleObserver
{

    public function saving(EmployeeShiftSchedule $employeeShiftSchedule)
    {
        if (user()) {
            $employeeShiftSchedule->last_updated_by = user()->id;
        }
    }

    public function creating(EmployeeShiftSchedule $employeeShiftSchedule)
    {
        if (user()) {
            $employeeShiftSchedule->added_by = user()->id;
        }
    }

    public function created(EmployeeShiftSchedule $employeeShiftSchedule)
    {
        if (user()) {
            event(new EmployeeShiftScheduleEvent($employeeShiftSchedule));
        }
    }

    public function updated(EmployeeShiftSchedule $employeeShiftSchedule)
    {
        if (user() && $employeeShiftSchedule->isDirty('employee_shift_id')) {
            event(new EmployeeShiftScheduleEvent($employeeShiftSchedule));
        }
    }

}
