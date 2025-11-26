<?php

declare(strict_types=1);

namespace App\Models;

/*
|--------------------------------------------------------------------------
| EventCategories (Event Categories) Model
|--------------------------------------------------------------------------
| This model represents the application event discount.
| It includes attributes and methods for managing event discount configurations.
*/

use Plugs\Base\Model\PlugModel;

/**
 * EventCategories (Event Categories) Model
 * 
 * @package App\Models
 */

class EventCategories extends PlugModel
{
    protected $table = 'event_categories';
    protected $primaryKey = 'id';
    protected $fillable = [
        'event_id',
        'type_id'
    ];

    protected $guarded = ['id'];

    protected $casts = [
        'id' => 'int',
        'event_id' => 'int',
        'type_id' => 'int'
    ];

    protected $timestamps = false;

    /**
     * Override to only set created_at
     */
    protected static function boot(): void
    {
        parent::boot();
    }

    /**
     * Relationship: Revision belongs to Event
     */
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }

    /**
     * Relationship: Revision belongs to Event (Type)
     */
    public function event_type()
    {
        return $this->belongsTo(EventType::class, 'event_id', 'id');
    }
}