<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    public static function map(): Collection
    {
        return static::query()->pluck('value', 'key');
    }

    public static function setValues(array $payload): void
    {
        foreach ($payload as $key => $value) {
            static::query()->updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }

    public static function valueOf(string $key, ?string $default = null): ?string
    {
        return static::query()->where('key', $key)->value('value') ?? $default;
    }
}
