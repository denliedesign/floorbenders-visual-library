<?php

namespace App\Enums;

enum RealmOrientation: string
{
    case Horizontal = 'horizontal';
    case Vertical = 'vertical';

    public function label(): string
    {
        return match ($this) {
            self::Horizontal => 'Horizontal',
            self::Vertical => 'Vertical',
        };
    }

    public function shortLabel(): string
    {
        return match ($this) {
            self::Horizontal => 'Horizontal',
            self::Vertical => 'Vertical',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Horizontal => 'Realms organized around a more horizontal body position.',
            self::Vertical => 'Realms organized around a more vertical body position.',
        };
    }

    public function badgePath(): ?string
    {
        return config("floorbenders.assets.badges.orientations.{$this->value}");
    }

    public function accentClass(): string
    {
        return match ($this) {
            self::Horizontal => 'fb-badge-slate',
            self::Vertical => 'fb-badge-teal',
        };
    }
}
