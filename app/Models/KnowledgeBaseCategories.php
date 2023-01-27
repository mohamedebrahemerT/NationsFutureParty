<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\KnowledgeBaseCategories
 *
 * @property int $id
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\KnowledgeBase[] $knowledgebase
 * @property-read int|null $knowledgebase_count
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeBaseCategories newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeBaseCategories newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeBaseCategories query()
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeBaseCategories whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeBaseCategories whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeBaseCategories whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeBaseCategories whereUpdatedAt($value)
 */

class KnowledgeBaseCategories extends Model
{
    use HasFactory;
    protected $table = 'knowledge_categories';

    public function knowledgebase()
    {
        return $this->hasMany(KnowledgeBase::class, 'category_id');
    }

}
