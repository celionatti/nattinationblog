<?php

declare(strict_types=1);

namespace App\Models;

/*
|--------------------------------------------------------------------------
| Resource Model
|--------------------------------------------------------------------------
| This model represents the application resources.
| It includes attributes and methods for managing resources configurations.
*/

use Plugs\Base\Model\PlugModel;

/**
 * Resource Model
 * 
 * @package App\Models
 */

class Resource extends PlugModel
{
    protected $table = 'resources';
    protected $primaryKey = 'id';
    protected $fillable = [
        'title',
        'description',
        'file_name',
        'file_path',
        'file_size',
        'file_type',
        'file_extension',
        'mime_type',
        'price',
        'is_free',
        'status',
        'download_count',
        'paid_download_count',
        'revenue_generated',
        'featured_image',
        'created_by',
        'published_at'
    ];

    protected $guarded = [];

    protected $casts = [
        'file_size' => 'int',
        'download_count' => 'int',
        'paid_download_count' => 'int',
        'created_by' => 'int',
        'published_at' => 'datetime',
    ];

    protected $timestamps = true;
    protected $softDelete = false;

    /**
     * Relationship: Resource belongs to User (created_by)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}