<?php

namespace App\Models;

use App\Observers\DiscussionReplyObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\DiscussionReply
 *
 * @property int $id
 * @property int $discussion_id
 * @property int $user_id
 * @property string $body
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Discussion $discussion
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|DiscussionReply newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DiscussionReply newQuery()
 * @method static \Illuminate\Database\Query\Builder|DiscussionReply onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|DiscussionReply query()
 * @method static \Illuminate\Database\Eloquent\Builder|DiscussionReply whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscussionReply whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscussionReply whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscussionReply whereDiscussionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscussionReply whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscussionReply whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscussionReply whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|DiscussionReply withTrashed()
 * @method static \Illuminate\Database\Query\Builder|DiscussionReply withoutTrashed()
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\DiscussionFile[] $files
 * @property-read int|null $files_count
 */
class DiscussionReply extends Model
{
    use SoftDeletes;

    protected static function boot()
    {
        parent::boot();
        static::observe(DiscussionReplyObserver::class);
    }

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function discussion()
    {
        return $this->belongsTo(Discussion::class);
    }

    public function files()
    {
        return $this->hasMany(DiscussionFile::class, 'discussion_reply_id');
    }

}
