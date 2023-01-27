<?php

namespace App\Models;

use App\Observers\EmployeeShiftChangeObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeShiftChangeRequest extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::observe(EmployeeShiftChangeObserver::class);
    }

    public function shiftSchedule()
    {
        return $this->belongsTo(EmployeeShiftSchedule::class, 'shift_schedule_id');
    }
    
    public function shift()
    {
        return $this->belongsTo(EmployeeShift::class, 'employee_shift_id');
    }
    
}
