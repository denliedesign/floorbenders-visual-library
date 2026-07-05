<?php

namespace App\Enums;

enum Realm: string
{
    case Insect = 'insect';
    case Reptile = 'reptile';
    case Mammal = 'mammal';
    case Amphibian = 'amphibian';
    case Bird = 'bird';
    case Fish = 'fish';

    public function label(): string
    {
        return match ($this) {
            self::Insect => 'Insect',
            self::Reptile => 'Reptile',
            self::Mammal => 'Mammal',
            self::Amphibian => 'Amphibian',
            self::Bird => 'Bird',
            self::Fish => 'Fish',
        };
    }

    public function shortLabel(): string
    {
        return match ($this) {
            self::Insect => 'Insect',
            self::Reptile => 'Reptile',
            self::Mammal => 'Mammal',
            self::Amphibian => 'Amphibian',
            self::Bird => 'Bird',
            self::Fish => 'Fish',
        };
    }

    public function metaLabel(): string
    {
        return $this->orientation()->label() . ' / ' . $this->layer()->label();
    }

    public function description(): string
    {
        return match ($this) {
            self::Insect => 'Supine or prone position.',
            self::Reptile => 'Seated or shoulderstand position.',
            self::Mammal => 'Crawling position.',
            self::Amphibian => 'Squat or handstand position.',
            self::Bird => 'Cricket or bridge position.',
            self::Fish => 'Kneel or elbowstand position.',
        };
    }

    public function orientation(): RealmOrientation
    {
        return match ($this) {
            self::Insect,
            self::Mammal,
            self::Bird => RealmOrientation::Horizontal,

            self::Reptile,
            self::Amphibian,
            self::Fish => RealmOrientation::Vertical,
        };
    }

    public function layer(): RealmLayer
    {
        return match ($this) {
            self::Insect,
            self::Reptile => RealmLayer::Grounded,

            self::Mammal,
            self::Amphibian => RealmLayer::Lifted,

            self::Bird,
            self::Fish => RealmLayer::Supported,
        };
    }

    public static function forOrientation(RealmOrientation|string $orientation): array
    {
        $orientation = is_string($orientation)
            ? RealmOrientation::from($orientation)
            : $orientation;

        return array_values(array_filter(
            self::cases(),
            fn (self $realm) => $realm->orientation() === $orientation
        ));
    }

    public static function forLayer(RealmLayer|string $layer): array
    {
        $layer = is_string($layer)
            ? RealmLayer::from($layer)
            : $layer;

        return array_values(array_filter(
            self::cases(),
            fn (self $realm) => $realm->layer() === $layer
        ));
    }

    public function badgePath(): ?string
    {
        return config("floorbenders.assets.badges.realms.{$this->value}");
    }

    public function accentClass(): string
    {
        return match ($this) {
            self::Insect => 'fb-badge-amber',
            self::Reptile => 'fb-badge-green',
            self::Mammal => 'fb-badge-rust',
            self::Amphibian => 'fb-badge-teal',
            self::Bird => 'fb-badge-slate',
            self::Fish => 'fb-badge-blue',
        };
    }
}
