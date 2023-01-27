<?php

namespace App\Models;

/**
 * App\Models\Designation
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $added_by
 * @property int|null $last_updated_by
 * @property-read mixed $icon
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\EmployeeDetails[] $members
 * @property-read int|null $members_count
 * @method static \Illuminate\Database\Eloquent\Builder|Designation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Designation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Designation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Designation whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Designation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Designation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Designation whereLastUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Designation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Designation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Designation extends BaseModel
{

    public function members()
    {
        return $this->hasMany(EmployeeDetails::class, 'designation_id');
    }

    public static function allDesignations()
    {
        if (user()->permission('view_designation') == 'all') {
            return Designation::all();
        }

        return Designation::where('added_by', user()->id)->get();
    }

}
