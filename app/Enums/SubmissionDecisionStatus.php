<?php

namespace App\Enums;

enum SubmissionDecisionStatus: string {
    case Pending = 'Pending'; 
    case UnderReview = 'Under review'; 
    case AwaitingPeerReview = 'Awaiting peer review';
    case Approved = 'Approved';
    case Declined = 'Declined';

    public static function names(): array {
        return array_map(fn($case) => $case->name, self::cases());
    }
    
    public static function values(): array {
        return array_map(fn($case) => $case->value, self::cases());
    }

    public static function toArray(): array {
        return array_column(
            array_map(fn($case) => [$case->name, $case->value], self::cases()),
            1,
            0
        );
    }
}