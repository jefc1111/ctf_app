<?php

namespace App\Enums;

enum SubmissionSubset: string
{
    case Total = 'total';
    case Team = 'team';
    case User = 'user';

    public function label(): string
    {
        return match($this) {
            self::Total => 'Total submissions',
            self::Team  => 'Team submissions',
            self::User  => 'Your submissions',
        };
    }
}