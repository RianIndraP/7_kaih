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
        // Jika di Production atau Lokal (menggunakan Laravel Herd HTTPS) → force HTTPS
        if (app()->environment('production') || app()->environment('local')) {
            URL::forceScheme('https');
        }
        // Ngrok
        else if (
            str_contains(request()->header('host'), 'ngrok-free.dev') ||
            str_contains(request()->header('host'), 'ngrok.io')
        ) {
            URL::forceScheme('https');
        }
    }
}
