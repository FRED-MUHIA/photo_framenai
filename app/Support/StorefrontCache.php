<?php

namespace App\Support;

use Illuminate\Support\Facades\Artisan;

class StorefrontCache
{
    public static function refresh(): void
    {
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
    }
}
