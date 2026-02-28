<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

//Schedule::command('ctf:simulate')->everyMinute();

// Re-seed every 12 hours
Schedule::call(function () {
    Artisan::call('down');
    Artisan::call('migrate:fresh', ['--seed' => true, '--force' => true]);
    Artisan::call('up');
})
    ->name('re-seed')
    ->twiceDaily(0, 12)
    ->withoutOverlapping();

// Only run simulate if app is NOT in maintenance mode
Schedule::command('ctf:simulate')
    ->everyMinute()
    ->skip(fn () => app()->isDownForMaintenance());