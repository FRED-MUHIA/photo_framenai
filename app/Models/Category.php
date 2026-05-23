<?php

namespace App\Models;

use App\Support\PublicAssetUrl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'image',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function linkedProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'category_product')->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getImageAttribute(?string $value): ?string
    {
        return PublicAssetUrl::resolve($value);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
