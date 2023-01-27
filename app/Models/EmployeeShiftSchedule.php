<?php

namespace App\Models;

use App\Observers\EmployeeShiftScheduleObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
* App\Models\EmployeeShiftSchedule
*
*
 * @property string|null $color
* @property-read \App\Models\EmployeeShift $shift
*
*/

class EmployeeShiftSchedule extends Model
{
    use HasFactory;

    protected $dates = ['date'];

    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::observe(EmployeeShiftScheduleObserver::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function shift()
    {
        return $this->belongsTo(EmployeeShift::class, 'employee_shift_id');
    }

    public function requestChange()
    {
        return $this->hasOne(EmployeeShiftChangeRequest::class, 'shift_schedule_id');
    }

}
