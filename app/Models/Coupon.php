<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_amount',
        'usage_limit',
        'used_count',
        'starts_at',
        'expires_at',
        'is_active',
        'metadata',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    public function isCurrentlyValid(float $amount): bool
    {
        $now = now();

        if (! $this->is_active || $amount < (float) $this->min_order_amount) {
            return false;
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return false;
        }

        if ($this->starts_at instanceof CarbonInterface && $now->lt($this->starts_at)) {
            return false;
        }

        return ! ($this->expires_at instanceof CarbonInterface && $now->gt($this->expires_at));
    }

    public function calculateDiscount(float $amount): float
    {
        if ($this->type === 'percentage') {
            return round($amount * (((float) $this->value) / 100), 2);
        }

        return min(round((float) $this->value, 2), $amount);
    }
}
