<?php

declare(strict_types=1);

namespace App\Models;

/*
|--------------------------------------------------------------------------
| Event Model
|--------------------------------------------------------------------------
| This model represents the application events.
| It includes attributes and methods for managing events configurations.
*/

use Plugs\Base\Model\PlugModel;

/**
 * Event Model
 * 
 * @package App\Models
 */

class Event extends PlugModel
{
    protected $table = 'events';
    protected $primaryKey = 'id';
    protected $fillable = [
        'title',
        'slug',
        'content',
        'event_date',
        'event_time',
        'location',
        'event_type',
        'event_image',
        'status',
        'author_id',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'published_at'
    ];

    protected $casts = [
        'event_date' => 'date',
        'published_at' => 'datetime',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tickets()
    {
        return $this->hasMany(EventTicket::class, 'event_id');
    }

    public function discount()
    {
        return $this->hasOne(EventDiscount::class, 'event_id');
    }

    public function event_types()
    {
        return $this->belongsToMany(EventType::class, 'event_categories', 'event_id', 'type_id');
    }
}