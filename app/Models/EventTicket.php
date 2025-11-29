<?php

declare(strict_types=1);

namespace App\Models;

/*
|--------------------------------------------------------------------------
| EventTicket Model
|--------------------------------------------------------------------------
| This model represents the application event tickets.
| It includes attributes and methods for managing event tickets configurations.
*/

use Plugs\Base\Model\PlugModel;

/**
 * EventTicket Model
 * 
 * @package App\Models
 */

class EventTicket extends PlugModel
{
    protected $table = 'event_tickets';
    protected $primaryKey = 'id';
    protected $fillable = [
        'event_id',
        'name',
        'price',
        'quantity',
        'description',
        'sale_start',
        'sale_end'
    ];

    protected $guarded = [];

    protected $casts = [
        'price' => 'float',
        'sale_start' => 'date:Y-m-d',
        'sale_end' => 'date:Y-m-d',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}