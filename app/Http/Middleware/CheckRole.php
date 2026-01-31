<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();

        if ($user->hasRole($roles)) {
            // User has the required role - let them through!            
            return $next($request);
        }
        
        // User is authenticated but doesn't have the right role
        // Redirect them to the suitable dashboard            
        if ($user->hasRole([ 'Senior Coach', 'Coach' ])) {
            return redirect()->intended('/coach');
        }
        
        if ($user->hasRole('Participant')) {            
            return redirect()->intended('/participant');
        }
        
        // No matching role - send to default dashboard or show error
        abort(403, 'You do not have permission to access this area.');

        //return redirect('/dashboard');
    }
}
