<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\RedirectResponse;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request): RedirectResponse
    {
        $user = auth()->user();
        
        // Role-based redirect logic
        if ($user->hasRole([ 'Super Admin', 'Admin', 'Event staff' ])) {
            return redirect()->intended('/admin');
        }
        
        if ($user->hasRole([ 'Senior Coach', 'Coach' ])) {
            return redirect()->intended('/coach');
        }
        
        if ($user->hasRole('Participant')) {
            return redirect()->intended('/participant/event-cases');
        }
        
        // Default fallback
        return redirect()->intended('/dashboard');
    }
}