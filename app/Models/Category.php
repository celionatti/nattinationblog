<?php

declare(strict_types=1);

namespace App\Models;

/*
|--------------------------------------------------------------------------
| Category Model
|--------------------------------------------------------------------------
| This model represents the categories.
| Represents article categories with hierarchy support.
*/

use Plugs\Base\Model\PlugModel;

class Category extends PlugModel
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'color',
        'is_active',
        'sort_order'
    ];

    protected $guarded = ['id'];

    protected $casts = [
        'id' => 'int',
        'parent_id' => 'int',
        'is_active' => 'bool',
        'sort_order' => 'int',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $timestamps = true;

    /**
     * Relationship: Category has many articles
     */
    public function articles()
    {
        return $this->belongsToMany(
            Article::class,
            'article_categories',
            'category_id',
            'article_id'
        );
    }

    /**
     * Relationship: Category belongs to parent category
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id');
    }

    /**
     * Relationship: Category has many children categories
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    /**
     * Scope: Get only active categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Get only parent categories (no parent_id)
     */
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope: Get categories by parent
     */
    public function scopeByParent($query, int $parentId)
    {
        return $query->where('parent_id', $parentId);
    }

    /**
     * Get article count for this category
     */
    public function getArticleCountAttribute(): int
    {
        // Count published articles
        $pdo = static::getPdo();
        $stmt = $pdo->prepare(
            "SELECT COUNT(*) as count 
             FROM article_categories ac
             JOIN articles a ON ac.article_id = a.id
             WHERE ac.category_id = ? AND a.status = 'published'"
        );
        $stmt->execute([$this->id]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        return (int) ($result['count'] ?? 0);
    }

    /**
     * Get category URL
     */
    public function getUrlAttribute(): string
    {
        return "/category/{$this->slug}";
    }

    /**
     * Check if category has children
     */
    public function hasChildren(): bool
    {
        return Category::where('parent_id', $this->id)->exists();
    }
}