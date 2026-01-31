<?php

use Illuminate\Support\Facades\Route;

// Root redirects to login if not authenticated, or to appropriate dashboard if authenticated
Route::get('/', function () {    
    if (auth()->check()) {
        $user = auth()->user();
        
        if ($user->hasRole([ 'Super Admin', 'Admin', 'Event staff' ])) {
            return redirect()->intended('/admin');
        }
        
        if ($user->hasRole([ 'Senior Coach', 'Coach' ])) {
            return redirect()->intended('/coach');
        }
        
        if ($user->hasRole('Participant')) {
            return redirect()->intended('/participant');
        }
                
        Auth::logout();

        // Optionally, you can perform a redirect after logging out
        return redirect('/login')->with('status', 'Invalid user: No roles assigned.'); 
    }
    
    return redirect('/login'); // Jetstream's login
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return redirect('/');

        //return view('dashboard');
    })->name('dashboard');
});
