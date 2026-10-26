<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Frame extends Model
{
    protected $fillable = [
        'name', 'slug', 'sku', 'description', 'material', 'asset_type',
        'thumbnail_path', 'overlay_path', 'landscape_overlay_path', 'texture_path',
        'base_price', 'min_width_cm', 'max_width_cm', 'width_price_per_cm',
        'orientations', 'sizes', 'mats', 'accessories', 'inventory', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'min_width_cm' => 'decimal:2',
        'max_width_cm' => 'decimal:2',
        'width_price_per_cm' => 'decimal:2',
        'orientations' => 'array',
        'sizes' => 'array',
        'mats' => 'array',
        'accessories' => 'array',
        'is_active' => 'boolean',
    ];
}
