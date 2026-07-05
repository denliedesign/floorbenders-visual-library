@props([
    'gate',
    'showLabel' => false,
    'size' => 'md',
])

@php
    $src = $gate->badgePath();
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
            alt="{{ $gate->label() }} badge"
            class="{{ $imageSizeClass }} object-contain"
            title="{{ $gate->label() }}"
        >

        @if ($showLabel)
            <span class="text-sm font-medium text-stone-200">
                {{ $gate->label() }}
            </span>
        @endif
    </span>
@else
    <x-ui.badge :class="$gate->accentClass()">
        <span>{{ $gate->shortLabel() }}</span>
    </x-ui.badge>
@endif
