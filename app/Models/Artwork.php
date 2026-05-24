<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Artwork extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_item_id',
        'guest_token',
        'original_name',
        'file_path',
        'mime_type',
        'extension',
        'size_bytes',
        'width_px',
        'height_px',
        'estimated_dpi',
        'validation_notes',
        'status',
        'reviewed_at',
    ];

    protected $casts = [
        'estimated_dpi' => 'decimal:2',
        'reviewed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getPublicUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }
}
