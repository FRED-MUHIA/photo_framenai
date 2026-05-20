<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductOptionValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_option_group_id',
        'label',
        'value',
        'price_modifier',
        'delivery_modifier',
        'metadata',
        'sort_order',
        'is_default',
    ];

    protected $casts = [
        'metadata' => 'array',
        'price_modifier' => 'decimal:2',
        'is_default' => 'boolean',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(ProductOptionGroup::class, 'product_option_group_id');
    }
}
