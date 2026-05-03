<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule::command('app:send-daily-notification')->everyMinute();  // Dev mode only
Schedule::command('app:send-daily-notification')->dailyAt('05:30');
// Schedule::command('app:send-daily-notification')->everyMinute();  // sementara
Schedule::command('app:send-daily-notification')->dailyAt('13:00');
Schedule::command('app:send-daily-notification')->dailyAt('16:00');
Schedule::command('app:send-daily-notification')->dailyAt('19:00');
Schedule::command('app:send-daily-notification')->dailyAt('20:00');