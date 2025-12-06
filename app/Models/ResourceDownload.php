<?php

declare(strict_types=1);

namespace App\Models;

/*
|--------------------------------------------------------------------------
| Resource Download Model
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

class ResourceDownload extends PlugModel
{
    protected $table = 'resource_downloads';
    protected $primaryKey = 'id';
    protected $fillable = [
        'resource_id',
        'user_id',
        'download_type',
        'amount_paid',
        'ip_address',
        'user_agent',
        'downloaded_at'
    ];

    protected $guarded = [];

    protected $casts = [
        'resource_id' => 'int',
        'user_id' => 'int',
        'amount_paid' => 'float',
        'downloaded_at' => 'datetime',
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