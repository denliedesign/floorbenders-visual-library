<?php

namespace App\Enums;

enum MediaProcessingStatus: string
{
    case Uploaded = 'uploaded';
    case TrimSet = 'trim_set';
    case Processing = 'processing';
    case Complete = 'complete';
    case Failed = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::Uploaded => 'Uploaded',
            self::TrimSet => 'Trim Set',
            self::Processing => 'Processing',
            self::Complete => 'Complete',
            self::Failed => 'Failed',
        };
    }
}
