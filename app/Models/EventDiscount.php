<?php

declare(strict_types=1);

namespace App\Models;

/*
|--------------------------------------------------------------------------
| EventDiscount Model
|--------------------------------------------------------------------------
| This model represents the application event discount.
| It includes attributes and methods for managing event discount configurations.
*/

use Plugs\Base\Model\PlugModel;

/**
 * EventDiscount Model
 * 
 * @package App\Models
 */

class EventDiscount extends PlugModel
{
    protected $table = 'event_discounts';
    protected $primaryKey = 'id';
    protected $fillable = [
        'event_id',
        'promo_code',
        'discount_type',
        'discount_value',
        'promo_valid_until',
        'promo_usage_limit',
        'used_count'
    ];

    protected $casts = [
        'discount_value' => 'float',
        'promo_valid_until' => 'date',
        'promo_usage_limit' => 'integer',
        'used_count' => 'integer'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}