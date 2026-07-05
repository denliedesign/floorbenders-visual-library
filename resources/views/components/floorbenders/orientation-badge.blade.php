@props([
    'orientation',
    'showLabel' => false,
    'size' => 'md',
])

@php
    $src = $orientation->badgePath();
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
            alt="{{ $orientation->label() }} badge"
            class="{{ $imageSizeClass }} object-contain"
            title="{{ $orientation->label() }}"
        >

        @if ($showLabel)
            <span class="text-sm font-medium text-stone-200">
                {{ $orientation->label() }}
            </span>
        @endif
    </span>
@else
    <x-ui.badge :class="$orientation->accentClass()">
        <span>{{ $orientation->shortLabel() }}</span>
    </x-ui.badge>
@endif
