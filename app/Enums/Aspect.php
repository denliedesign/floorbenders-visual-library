<?php

namespace App\Enums;

enum Aspect: string
{
    case Sky = 'sky';
    case Earth = 'earth';

    public function label(): string
    {
        return match ($this) {
            self::Sky => 'Sky',
            self::Earth => 'Earth',
        };
    }

    public function shortLabel(): string
    {
        return match ($this) {
            self::Sky => 'Sky',
            self::Earth => 'Earth',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Sky => 'Within a realm, when the head or face is oriented toward the sky.',
            self::Earth => 'Within a realm, when the head or face is oriented toward the earth.',
        };
    }

    public function badgePath(): ?string
    {
        return config("floorbenders.assets.badges.aspects.{$this->value}");
    }

    public function accentClass(): string
    {
        return match ($this) {
            self::Sky => 'fb-badge-blue',
            self::Earth => 'fb-badge-green',
        };
    }
}
