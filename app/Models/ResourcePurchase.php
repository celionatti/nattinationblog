<?php

declare(strict_types=1);

namespace App\Models;

/*
|--------------------------------------------------------------------------
| Resource Purchase Model
|--------------------------------------------------------------------------
| This model represents the application resources.
| It includes attributes and methods for managing resources configurations.
*/

use Plugs\Base\Model\PlugModel;

/**
 * Resource Download Model
 * 
 * @package App\Models
 */

class ResourcePurchase extends PlugModel
{
    protected $table = 'resource_purchases';
    protected $primaryKey = 'id';
    protected $fillable = [
        'resource_id',
        'user_id',
        'amount',
        'payment_status',
        'payment_method',
        'transaction_id',
        'purchased_at'
    ];

    protected $guarded = [];

    protected $casts = [
        'resource_id' => 'int',
        'user_id' => 'int',
        'purchased_at' => 'datetime',
    ];

    protected $timestamps = false;

    /**
     * Relationship: Resource Download belongs to Resource (resource_id)
     */
    public function resource()
    {
        return $this->belongsTo(Resource::class, 'resource_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}