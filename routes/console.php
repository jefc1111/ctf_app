<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Event;

Schedule::call(function (): void {
    if (! config('ctf.run_simulation')) {
        return;
    }
    
    $activeEvents = Event::where('simulate_activity', true)
        ->get()
        ->filter(fn($e) => $e->isInProgress());
    
    if ($activeEvents->isEmpty()) {
        \Log::warning('No active events - re-seeding database.');

        Artisan::call('down');
        Artisan::call('migrate:fresh', ['--seed' => true, '--force' => true]);
        Artisan::call('up');    
    } else {
        \Log::info('An event is in progress - running a simulation step.');

        Artisan::call('ctf:simulate');
    }
})->name('update-simulation')
    ->everyMinute()
    ->withoutOverlapping();

    