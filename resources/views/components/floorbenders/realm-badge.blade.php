@props([
    'realm',
    'showLabel' => false,
    'size' => 'md',
])

@php
    $src = $realm->badgePath();
    $publicPath = $src ? public_path(ltrim($src, '/')) : null;
    $imageExists = $publicPath && file_exists($publicPath);

    $imageSizeClass = match ($size) {
        'sm' => 'h-8 w-8',
        'lg' => 'h-16 w-16',
        'xl' => 'h-24 w-24',
        default => 'h-12 w-12',
    };
@endphp

@if ($imageExists)
    <span {{ $attributes->class('inline-flex items-center gap-2') }}>
        <img
            src="{{ $src }}"
            alt="{{ $realm->label() }} badge"
            class="{{ $imageSizeClass }} object-contain"
            title="{{ $realm->label() }}"
        >

        @if ($showLabel)
            <span class="text-sm font-medium text-stone-200">
                {{ $realm->label() }}
            </span>
        @endif
    </span>
@else
    <x-ui.badge :class="$realm->accentClass()">
        <span>{{ $realm->label() }}</span>
    </x-ui.badge>
@endif
