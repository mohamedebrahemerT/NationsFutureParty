<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\KnowledgeBase
 *
 * @property int $id
 * @property string $to
 * @property string $heading
 * @property int|null $category_id
 * @property string|null $description
 * @property int $added_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\KnowledgeBaseCategories|null $knowledgebasecategory
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeBase newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeBase newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeBase query()
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeBase whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeBase whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeBase whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeBase whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeBase whereHeading($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeBase whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeBase whereTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|KnowledgeBase whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class KnowledgeBase extends Model
{
    use HasFactory;

    public function knowledgebasecategory()
    {
        return $this->belongsTo(KnowledgeBaseCategories::class, 'category_id');
    }

}
