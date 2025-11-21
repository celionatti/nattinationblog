<?php

declare(strict_types=1);

namespace App\Models;

/*
|--------------------------------------------------------------------------
| Article Categories Model
|--------------------------------------------------------------------------
| This model represents the article categories.
| Tracks article changes and draft versions.
*/

use Plugs\Base\Model\PlugModel;

class ArticleCategories extends PlugModel
{
    protected $table = 'article_categories';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'article_id',
        'category_id',
    ];

    protected $guarded = ['id'];

    protected $casts = [
        'id' => 'int',
        'article_id' => 'int',
        'category_id' => 'int'
    ];

    protected $timestamps = false; // Only uses created_at
    
    /**
     * Override to only set created_at
     */
    protected static function boot(): void
    {
        parent::boot();
    }

    /**
     * Relationship: Revision belongs to Article
     */
    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id', 'id');
    }

    /**
     * Relationship: Revision belongs to User (Category)
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    /**
     * Get formatted created date
     */
    public function getCreatedDateAttribute(): string
    {
        return date('F j, Y g:i A', strtotime($this->created_at));
    }
}