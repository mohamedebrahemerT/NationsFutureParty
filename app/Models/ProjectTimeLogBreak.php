<?php

namespace App\Models;

use App\Observers\ProjectTimelogBreakObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProjectTimeLogBreak
 *
 * @property int $id
 * @property int|null $project_time_log_id
 * @property \Illuminate\Support\Carbon $start_time
 * @property \Illuminate\Support\Carbon|null $end_time
 * @property string $reason
 * @property string|null $total_hours
 * @property string|null $total_minutes
 * @property int|null $added_by
 * @property int|null $last_updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ProjectTimeLog|null $timelog
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectTimeLogBreak newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectTimeLogBreak newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectTimeLogBreak query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectTimeLogBreak whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectTimeLogBreak whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectTimeLogBreak whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectTimeLogBreak whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectTimeLogBreak whereLastUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectTimeLogBreak whereProjectTimeLogId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectTimeLogBreak whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectTimeLogBreak whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectTimeLogBreak whereTotalHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectTimeLogBreak whereTotalMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectTimeLogBreak whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProjectTimeLogBreak extends Model
{
    use HasFactory;

    protected $dates = ['start_time', 'end_time'];

    protected static function boot()
    {
        parent::boot();
        static::observe(ProjectTimelogBreakObserver::class);
    }

    public function timelog()
    {
        return $this->belongsTo(ProjectTimeLog::class, 'project_time_log_id');
    }

    public static function projectBreakMinutes($projectID)
    {
        return ProjectTimeLogBreak::join('project_time_logs', 'project_time_log_breaks.project_time_log_id', '=', 'project_time_logs.id')
            ->where('project_time_logs.project_id', $projectID)
            ->sum('project_time_log_breaks.total_minutes');
    }

    public static function taskBreakMinutes($taskID)
    {
        return ProjectTimeLogBreak::join('project_time_logs', 'project_time_log_breaks.project_time_log_id', '=', 'project_time_logs.id')
            ->where('project_time_logs.task_id', $taskID)
            ->sum('project_time_log_breaks.total_minutes');
    }

    public static function userBreakMinutes($userID)
    {
        return ProjectTimeLogBreak::join('project_time_logs', 'project_time_log_breaks.project_time_log_id', '=', 'project_time_logs.id')
            ->where('project_time_logs.user_id', $userID)
            ->sum('project_time_log_breaks.total_minutes');
    }

    public static function milestoneBreakMinutes($milestoneID)
    {
        return ProjectTimeLogBreak::join('project_time_logs', 'project_time_log_breaks.project_time_log_id', '=', 'project_time_logs.id')
            ->join('project_milestones', 'project_milestones.project_id', '=', 'project_time_logs.project_id')
            ->where('project_milestones.id', $milestoneID)
            ->sum('project_time_log_breaks.total_minutes');
    }

}
