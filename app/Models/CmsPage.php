<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'layout',
        'body',
        'sections',
        'meta_title',
        'meta_description',
        'tags',
        'is_active',
    ];

    protected $casts = [
        'sections' => 'array',
        'is_active' => 'boolean',
        'tags' => 'array',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
