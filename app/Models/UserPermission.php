<?php

namespace App\Models;

use App\Observers\UserObserver;
use App\Observers\UserPermissionObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserPermission
 *
 * @property int $id
 * @property int $user_id
 * @property int $permission_id
 * @property int $permission_type_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Permission $permission
 * @property-read \App\Models\PermissionType $type
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission wherePermissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission wherePermissionTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission whereUserId($value)
 * @mixin \Eloquent
 */
class UserPermission extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();
        static::observe(UserPermissionObserver::class);
    }

    /**
     * XXXXXXXXXXX
     *
     * @return \Illuminate\Http\Response
     */
    public function type()
    {
        return $this->belongsTo(PermissionType::class, 'permission_type_id');
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
