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

class Escrow extends PlugModel
{
    protected $table = 'escrows';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'color',
        'icon',
        'is_active',
        'sort_order'
    ];

    protected $guarded = ['id'];

    protected $casts = [
        'id' => 'int',
    ];

    protected $timestamps = true;
}