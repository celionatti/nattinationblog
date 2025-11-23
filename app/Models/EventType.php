<?php

declare(strict_types=1);

namespace App\Models;

/*
|--------------------------------------------------------------------------
| EventType (Categories) Model
|--------------------------------------------------------------------------
| This model represents the application event discount.
| It includes attributes and methods for managing event discount configurations.
*/

use Plugs\Base\Model\PlugModel;

/**
 * EventType (Categories) Model
 * 
 * @package App\Models
 */

class EventType extends PlugModel
{
    protected $table = 'event_types';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'icon',
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
     * Relationship: Event Types has many events
     */
    public function events()
    {
        return $this->belongsToMany(
            Event::class,
            'event_categories',
            'type_id',
            'event_id'
        );
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    /**
     * Scope: Get only active categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}