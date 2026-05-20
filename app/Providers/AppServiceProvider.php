<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\SiteSetting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        View::composer('*', function ($view) {
            $categories = collect();
            $siteSettings = collect();

            if (Schema::hasTable('categories')) {
                $categories = Category::query()
                    ->active()
                    ->orderBy('sort_order')
                    ->get(['id', 'name', 'slug']);
            }

            if (Schema::hasTable('site_settings')) {
                $siteSettings = SiteSetting::map();
            }

            $view->with('navCategories', $categories);
            $view->with('shopConfig', config('shop'));
            $view->with('siteSettings', $siteSettings);
        });
    }
}
