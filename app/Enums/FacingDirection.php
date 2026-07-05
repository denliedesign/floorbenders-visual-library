<?php

namespace App\Enums;

enum FacingDirection:string
{
    case Downstage = 'downstage';
    case Upstage = 'upstage';
    case StageLeft = 'stage-left';
    case StageRight = 'stage-right';
    case Unknown = 'unknown';

    public function label(): string
    {
        return match ($this) {
            self::Downstage => 'Downstage',
            self::Upstage => 'Upstage',
            self::StageLeft => 'Stage Left',
            self::StageRight => 'Stage Right',
            self::Unknown => 'Unknown',
        };
    }
}
