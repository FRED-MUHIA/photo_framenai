<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'short_description',
        'description',
        'base_price',
        'min_quantity',
        'base_delivery_days',
        'thumbnail',
        'metadata',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'metadata' => 'array',
        'base_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_product')->withTimestamps();
    }

    public function mediaItems(): BelongsToMany
    {
        return $this->belongsToMany(MediaItem::class, 'media_item_product')
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderByPivot('sort_order');
    }

    public function optionGroups(): HasMany
    {
        return $this->hasMany(ProductOptionGroup::class)->orderBy('sort_order');
    }

    public function quantityPrices(): HasMany
    {
        return $this->hasMany(ProductQuantityPrice::class)->where('is_active', true)->orderBy('quantity');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function defaultSelections(): array
    {
        $defaults = [];

        $groups = $this->relationLoaded('optionGroups') ? $this->optionGroups : $this->optionGroups()->with('values')->get();

        /** @var ProductOptionGroup $group */
        foreach ($groups as $group) {
            $option = $group->values->firstWhere('is_default', true) ?? $group->values->first();
            if ($option) {
                $defaults[$group->code] = $option->id;
            }
        }

        return $defaults;
    }

    public function availableQuantities(): Collection
    {
        $prices = $this->relationLoaded('quantityPrices') ? $this->quantityPrices : $this->quantityPrices()->get();

        return $prices->pluck('quantity');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
