<?php

namespace App\Models;

use App\Observers\LeaveObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Leave
 *
 * @property int $id
 * @property int $user_id
 * @property int $leave_type_id
 * @property string $duration
 * @property \Illuminate\Support\Carbon $leave_date
 * @property string $reason
 * @property string $status
 * @property string|null $reject_reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $paid
 * @property int|null $added_by
 * @property int|null $last_updated_by
 * @property-read mixed $date
 * @property-read mixed $icon
 * @property-read mixed $leaves_taken_count
 * @property-read \App\Models\LeaveType $type
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\LeaveFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Leave newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Leave newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Leave query()
 * @method static \Illuminate\Database\Eloquent\Builder|Leave whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leave whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leave whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leave whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leave whereLastUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leave whereLeaveDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leave whereLeaveTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leave wherePaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leave whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leave whereRejectReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leave whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leave whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Leave whereUserId($value)
 * @mixin \Eloquent
 * @property string|null $event_id
 * @method static \Illuminate\Database\Eloquent\Builder|Leave whereEventId($value)
 */
class Leave extends BaseModel
{
    use HasFactory;

    protected $dates = ['leave_date'];
    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();
        static::observe(LeaveObserver::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withoutGlobalScopes(['active']);
    }

    public function type()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }

    public function getLeavesTakenCountAttribute()
    {
        $userId = $this->user_id;
        $setting = global_setting();
        $user = User::withoutGlobalScope('active')->findOrFail($userId);

        if ($setting->leaves_start_from == 'joining_date') {
            $fullDay = Leave::where('user_id', $userId)
                ->where('leave_date', '<=', $user->employee[0]->joining_date->format((Carbon::now()->year + 1) . '-m-d'))
                ->where('status', 'approved')
                ->where('duration', '<>', 'half day')
                ->count();

            $halfDay = Leave::where('user_id', $userId)
                ->where('leave_date', '<=', $user->employee[0]->joining_date->format((Carbon::now()->year + 1) . '-m-d'))
                ->where('status', 'approved')
                ->where('duration', 'half day')
                ->count();

            return ($fullDay + ($halfDay / 2));
        }
        else {
            $fullDay = Leave::where('user_id', $userId)
                ->where('leave_date', '<=', Carbon::today()->endOfYear()->format('Y-m-d'))
                ->where('status', 'approved')
                ->where('duration', '<>', 'half day')
                ->count();

            $halfDay = Leave::where('user_id', $userId)
                ->where('leave_date', '<=', Carbon::today()->endOfYear()->format('Y-m-d'))
                ->where('status', 'approved')
                ->where('duration', 'half day')
                ->count();

            return ($fullDay + ($halfDay / 2));
        }

    }

    public static function byUser($userId)
    {
        $setting = global_setting();
        $user = User::withoutGlobalScope('active')->findOrFail($userId);

        if ($setting->leaves_start_from == 'joining_date' && isset($user->employee[0])) {
            return Leave::where('user_id', $userId)
                ->where('leave_date', '<=', $user->employee[0]->joining_date->format((Carbon::now()->year + 1) . '-m-d'))
                ->where('status', 'approved')
                ->get();
        }
        else {
            return Leave::where('user_id', $userId)
                ->where('leave_date', '<=', Carbon::today()->endOfYear()->format('Y-m-d'))
                ->where('status', 'approved')
                ->get();
        }
    }

    public static function byUserCount($userId)
    {
        $setting = global_setting();
        $user = User::withoutGlobalScope('active')->findOrFail($userId);

        if ($setting->leaves_start_from == 'joining_date' && isset($user->employee[0])) {
            $fullDay = Leave::where('user_id', $userId)
                ->where('leave_date', '<=', $user->employee[0]->joining_date->format((Carbon::now()->year + 1) . '-m-d'))
                ->where('status', 'approved')
                ->where('duration', '<>', 'half day')
                ->get();

            $halfDay = Leave::where('user_id', $userId)
                ->where('leave_date', '<=', $user->employee[0]->joining_date->format((Carbon::now()->year + 1) . '-m-d'))
                ->where('status', 'approved')
                ->where('duration', 'half day')
                ->get();

            return (count($fullDay) + (count($halfDay) / 2));

        } else {
            $fullDay = Leave::where('user_id', $userId)
                ->where('leave_date', '<=', Carbon::today()->endOfYear()->format('Y-m-d'))
                ->where('status', 'approved')
                ->where('duration', '<>', 'half day')
                ->get();

            $halfDay = [];

            if(isset($user->employee[0])){
                $halfDay = Leave::where('user_id', $userId)
                    ->where('leave_date', '<=', $user->employee[0]->joining_date->format((Carbon::now()->year + 1) . '-m-d'))
                    ->where('status', 'approved')
                    ->where('duration', 'half day')
                    ->get();
            }

            return (count($fullDay) + (count($halfDay) / 2));
        }
    }

}
