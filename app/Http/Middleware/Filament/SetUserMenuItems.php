<?php

namespace App\Http\Middleware\Filament;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Filament\Actions\Action;

class SetUserMenuItems
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return $next($request);
        }

        $profileMenuItems = [
            'profile' => fn (Action $action) => $action->url('/user/profile'),
        ];

        $adminRoles = [
            'Super Admin', 
            'Admin',
            'Event Staff'
        ];

        $dashboardRoleMappings = [
            'admin' => $adminRoles,
            'coach' => [
                ...$adminRoles,
                'Senior Coach',
                'Coach'
            ],
            'participant' => [
                ...$adminRoles,
                'Participant'
            ]
        ];

        $dashboardIcons = [
            'admin' => 'heroicon-o-wrench-screwdriver',
            'coach' => 'heroicon-o-puzzle-piece',
            'participant' => 'heroicon-o-truck'
        ];

        foreach ($dashboardRoleMappings as $dashboard => $roles) {
            if (auth()->user()->hasRole($roles)) {
                $profileMenuItems = [       
                    ...$profileMenuItems,
                    Action::make("$dashboard-dashboard")
                        ->url(fn (): string => "/$dashboard")
                        ->icon($dashboardIcons[$dashboard])
                ];
            }
        }

        filament()->getCurrentPanel()->userMenuItems($profileMenuItems);

        return $next($request);
    }
}