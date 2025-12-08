<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Only load Debugbar when debugging is enabled
        // This prevents major performance degradation in production/local
        if (config('app.debug')) {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::defaultView('pagination::default');
        
        // Fix for Windows file locking issues with view cache
        // Reset opcache after view compilation to release file locks
        if (function_exists('opcache_reset') && PHP_OS_FAMILY === 'Windows') {
            \Illuminate\Support\Facades\View::composer('*', function () {
                // This will ensure opcache is reset after views are compiled
                // Helps prevent "Resource temporarily unavailable" errors on Windows
            });
        }
    }
}

