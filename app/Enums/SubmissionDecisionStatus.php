<?php

namespace App\Enums;

enum SubmissionDecisionStatus: string {
    case PENDING = 'Pending'; 
    case UNDER_REVIEW = 'Under review'; 
    case AWAITING_PEER_REVIEW = 'Awaiting peer review';
    case APPROVED = 'Approved';
    case DECLINED = 'Declined';

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