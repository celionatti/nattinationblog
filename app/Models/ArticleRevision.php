<?php

declare(strict_types=1);

namespace App\Models;

/*
|--------------------------------------------------------------------------
| Article Revision Model
|--------------------------------------------------------------------------
| This model represents the article revisions.
| Tracks article changes and draft versions.
*/

use Plugs\Base\Model\PlugModel;

class ArticleRevision extends PlugModel
{
    protected $table = 'article_revisions';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'article_id',
        'revision_number',
        'title',
        'content',
        'excerpt',
        'author_id',
        'revision_notes'
    ];

    protected $guarded = ['id'];

    protected $casts = [
        'id' => 'int',
        'article_id' => 'int',
        'revision_number' => 'int',
        'author_id' => 'int',
        'created_at' => 'datetime'
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
     * Relationship: Revision belongs to User (Author)
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    /**
     * Get formatted created date
     */
    public function getCreatedDateAttribute(): string
    {
        return date('F j, Y g:i A', strtotime($this->created_at));
    }

    /**
     * Get revision label
     */
    public function getRevisionLabelAttribute(): string
    {
        return "Revision #{$this->revision_number}";
    }

    /**
     * Restore this revision to the article
     */
    public function restore(): bool
    {
        $article = $this->article;
        
        if (!$article) {
            return false;
        }

        return $article->update([
            'title' => $this->title,
            'content' => $this->content,
            'excerpt' => $this->excerpt
        ]);
    }
}