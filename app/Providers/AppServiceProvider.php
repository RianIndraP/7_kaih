<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
    // Production → force HTTPS
    if (app()->environment('production')) {
        URL::forceScheme('https');
    }
    // Ngrok
    else if (
        str_contains(request()->header('host'), 'ngrok-free.dev') ||
        str_contains(request()->header('host'), 'ngrok.io')
    ) {
        URL::forceScheme('https');
    }
    // Lokal
    else if (app()->environment('local')) {
        URL::forceScheme('http');
    }
}

}
