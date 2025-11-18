<?php

declare(strict_types=1);

namespace App\Models;

/*
|--------------------------------------------------------------------------
| Article Model
|--------------------------------------------------------------------------
| This model represents the articles.
| Represents blog articles/posts.
*/

use Plugs\Base\Model\PlugModel;

class Article extends PlugModel
{
    protected $table = 'articles';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'featured_image',
        'status',
        'author_id',
        'view_count',
        'comment_count',
        'like_count',
        'categories',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'published_at'
    ];

    protected $guarded = ['id'];

    protected $casts = [
        'id' => 'int',
        'author_id' => 'int',
        'view_count' => 'int',
        'comment_count' => 'int',
        'like_count' => 'int',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $timestamps = true;
    protected $softDelete = false;

    /**
     * Relationship: Article belongs to User (Author)
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    /**
     * Relationship: Article has many revisions
     */
    public function revisions()
    {
        return $this->hasMany(ArticleRevision::class, 'article_id', 'id');
    }

    /**
     * Relationship: Article belongs to many categories
     */
    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'article_categories',
            'article_id',
            'category_id'
        );
    }

    /**
     * Get latest revision
     */
    public function latestRevision()
    {
        return $this->hasOne(ArticleRevision::class, 'article_id', 'id')
            ->orderBy('revision_number', 'DESC');
    }

    /**
     * Scope: Get only published articles
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->where('published_at', '<=', date('Y-m-d H:i:s'));
    }

    /**
     * Scope: Get only draft articles
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope: Get archived articles
     */
    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    /**
     * Scope: Search articles by keyword
     */
    public function scopeSearch($query, string $term)
    {
        return $query->where(function($q) use ($term) {
            $q->whereLike('title', "%{$term}%")
              ->orWhereLike('content', "%{$term}%")
              ->orWhereLike('excerpt', "%{$term}%");
        });
    }

    /**
     * Scope: Filter by category
     */
    public function scopeByCategory($query, int $categoryId)
    {
        return $query->whereRaw(
            "EXISTS (SELECT 1 FROM article_categories WHERE article_id = articles.id AND category_id = ?)",
            [$categoryId]
        );
    }

    /**
     * Check if article is published
     */
    public function isPublished(): bool
    {
        return $this->status === 'published' && 
               $this->published_at !== null && 
               strtotime($this->published_at) <= time();
    }

    /**
     * Check if article is draft
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Check if article is archived
     */
    public function isArchived(): bool
    {
        return $this->status === 'archived';
    }

    /**
     * Get article URL
     */
    public function getUrlAttribute(): string
    {
        return "/articles/{$this->slug}/{$this->id}";
    }

    /**
     * Get formatted published date
     */
    public function getPublishedDateAttribute(): string
    {
        if ($this->published_at) {
            return date('F j, Y', strtotime($this->published_at));
        }
        return 'Not published';
    }

    /**
     * Get reading time estimate
     */
    public function getReadingTimeAttribute(): string
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $minutes = max(1, ceil($wordCount / 200)); // Average reading speed
        return $minutes . ' min read';
    }

    /**
     * Get excerpt with fallback
     */
    public function getExcerptAttribute($value): string
    {
        if (!empty($value)) {
            return $value;
        }

        // Generate excerpt from content if not set
        $text = strip_tags($this->content);
        $text = preg_replace('/\s+/', ' ', trim($text));
        
        if (strlen($text) > 200) {
            $text = substr($text, 0, 200);
            $lastSpace = strrpos($text, ' ');
            if ($lastSpace !== false) {
                $text = substr($text, 0, $lastSpace);
            }
            $text .= '...';
        }

        return $text;
    }

    /**
     * Increment view count
     */
    public function incrementViews(): bool
    {
        return $this->increment('view_count');
    }

    /**
     * Increment like count
     */
    public function incrementLikes(): bool
    {
        return $this->increment('like_count');
    }

    /**
     * Decrement like count
     */
    public function decrementLikes(): bool
    {
        if ($this->like_count > 0) {
            return $this->decrement('like_count');
        }
        return false;
    }

    /**
     * Update comment count
     */
    public function updateCommentCount(): bool
    {
        // This would be updated when comments are added/removed
        // Implementation depends on your comment system
        return true;
    }
}