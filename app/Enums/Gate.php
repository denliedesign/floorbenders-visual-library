<?php

namespace App\Enums;

enum Gate: string
{
    case VGate = 'v-gate';
    case ZGate = 'z-gate';
    case LGate = 'l-gate';
    case HGate = 'h-gate';

    public function label(): string
    {
        return match ($this) {
            self::VGate => 'V-Gate',
            self::ZGate => 'Z-Gate',
            self::LGate => 'L-Gate',
            self::HGate => 'H-Gate',
        };
    }

    public function shortLabel(): string
    {
        return match ($this) {
            self::VGate => 'V',
            self::ZGate => 'Z',
            self::LGate => 'L',
            self::HGate => 'H',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::VGate => 'A direct pathway between realms through a kneeling position.',
            self::ZGate => 'A direct pathway between realms through a z-sit position.',
            self::LGate => 'A direct pathway between realms through a one leg straight, one leg bent, sitting position.',
            self::HGate => 'A direct pathway between realms through a hook position.',
        };
    }

    public function badgePath(): ?string
    {
        return config("floorbenders.assets.badges.gates.{$this->value}");
    }

    public function accentClass(): string
    {
        return match ($this) {
            self::VGate => 'fb-badge-amber',
            self::ZGate => 'fb-badge-teal',
            self::LGate => 'fb-badge-blue',
            self::HGate => 'fb-badge-green',
        };
    }
}
