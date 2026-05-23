<?php

namespace App\Models;

use App\Support\PublicAssetUrl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MediaItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'file_path',
        'external_url',
        'mime_type',
        'size',
        'alt_text',
        'created_by',
    ];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'media_item_product')
            ->withPivot('sort_order')
            ->withTimestamps();
    }

    public function getUrlAttribute(): ?string
    {
        if ($this->type === 'link') {
            return PublicAssetUrl::resolve($this->external_url);
        }

        if (! $this->file_path) {
            return null;
        }

        $path = ltrim(str_replace('\\', '/', $this->file_path), '/');

        return PublicAssetUrl::resolve('/storage/' . $path);
    }
}
