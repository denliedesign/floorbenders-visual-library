<?php

namespace App\Enums;

enum RealmLayer: string
{
    case Grounded = 'grounded';
    case Lifted = 'lifted';
    case Supported = 'supported';

    public function label(): string
    {
        return match ($this) {
            self::Grounded => 'Grounded',
            self::Lifted => 'Lifted',
            self::Supported => 'Supported',
        };
    }

    public function shortLabel(): string
    {
        return match ($this) {
            self::Grounded => 'Grounded',
            self::Lifted => 'Lifted',
            self::Supported => 'Supported',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Grounded => 'Realms organized around positions that are low and have weight grounded into the floor across multiple points of contact.',
            self::Lifted => 'Realms organized around having the body lifted off the floor and weight distributed equally across the hands and/or feet.',
            self::Supported => 'Realms organized around the body being supported unconventionally in difficult positions, such as with heads, elbows, and knees.',
        };
    }

    public function badgePath(): ?string
    {
        return config("floorbenders.assets.badges.layers.{$this->value}");
    }

    public function accentClass(): string
    {
        return match ($this) {
            self::Grounded => 'fb-badge-green',
            self::Lifted => 'fb-badge-amber',
            self::Supported => 'fb-badge-blue',
        };
    }
}
