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
        // 1. Cek apakah diakses lewat Ngrok
        if (
            str_contains(request()->header('host'), 'ngrok-free.dev') ||
            str_contains(request()->header('host'), 'ngrok.io')
        ) {

            // Paksa HTTPS agar asset (CSS/JS) terbaca benar di Ngrok
            URL::forceScheme('https');
        }
        // 2. Jika di lokal (Laragon)
        else if (app()->environment('local')) {

            // Paksa HTTP agar tidak kena "Refused to Connect" di Laragon
            URL::forceScheme('http');
        }
    }
}
