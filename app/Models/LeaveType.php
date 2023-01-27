<?php

namespace App\Models;

use App\Observers\LeaveTypeObserver;
use Carbon\Carbon;

/**
 * App\Models\LeaveType
 *
 * @property int $id
 * @property string $type_name
 * @property string $color
 * @property int $no_of_leaves
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $paid
 * @property-read mixed $icon
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Leave[] $leaves
 * @property-read int|null $leaves_count
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveType query()
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveType whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveType whereNoOfLeaves($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveType wherePaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveType whereTypeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LeaveType extends BaseModel
{

    protected static function boot()
    {
        parent::boot();
        static::observe(LeaveTypeObserver::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class, 'leave_type_id');
    }

    public function leavesCount()
    {
        return $this->leaves()
            ->selectRaw('leave_type_id, count(*) as count, SUM(if(duration="half day", 1, 0)) AS halfday')
            ->groupBy('leave_type_id');
    }

    public static function byUser($userId, $leaveTypeId = null)
    {
        $setting = global_setting();
        $user = User::withoutGlobalScope('active')->findOrFail($userId);

        if(isset($user->employee[0])) {
            if ($setting->leaves_start_from == 'joining_date') {
                $leaveTypes = LeaveType::with(['leavesCount' => function ($q) use ($user, $userId) {
                    $q->where('leaves.user_id', $userId);
                    $q->where('leaves.leave_date', '<=', $user->employee[0]->joining_date->format((Carbon::now()->year + 1) . '-m-d'));
                    $q->where('leaves.status', 'approved');
                }]);

                if (!is_null($leaveTypeId)) {
                    $leaveTypes = $leaveTypes->where('id', $leaveTypeId);
                }

                return $leaveTypes = $leaveTypes->get();
            }
            else {
                $leaveTypes = LeaveType::with(['leavesCount' => function ($q) use ($user, $userId) {
                    $q->where('leaves.user_id', $userId);
                    $q->where('leaves.leave_date', '<=', $user->employee[0]->joining_date->format((Carbon::now()->year + 1) . '-m-d'));
                    $q->where('leaves.status', 'approved');
                }]);

                if (!is_null($leaveTypeId)) {
                    $leaveTypes = $leaveTypes->where('id', $leaveTypeId);
                }

                return $leaveTypes = $leaveTypes->get();
            }
        }

        return [];
    }

}
