<?php

namespace App\Enums;

enum SequenceMediaProcessingStatus: string
{
    case Missing = 'missing';
    case Processing = 'processing';
    case Complete = 'complete';
    case Failed = 'failed';
    case Stale = 'stale';

    public function label(): string
    {
        return match ($this) {
            self::Missing => 'Missing',
            self::Processing => 'Processing',
            self::Complete => 'Complete',
            self::Failed => 'Failed',
            self::Stale => 'Stale',
        };
    }

    public function badgeVariant(): string
    {
        return match ($this) {
            self::Missing => 'slate',
            self::Processing => 'amber',
            self::Complete => 'teal',
            self::Failed => 'danger',
            self::Stale => 'amber',
        };
    }
}
