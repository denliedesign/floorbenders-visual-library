@props([
    'variant' => 'primary',
    'type' => 'button',
    'href' => null,
    'navigate' => false,
])

@php
    $classes = match ($variant) {
        'primary' => 'fb-button-primary',
        'secondary' => 'fb-button-secondary',
        'danger' => 'fb-button-danger',
        'ghost' => 'inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold text-stone-300 transition hover:bg-stone-900 hover:text-stone-100',
        default => 'fb-button-primary',
    };
@endphp

@if ($href)
    <a
        href="{{ $href }}"
        @if ($navigate) wire:navigate @endif
        {{ $attributes->class($classes) }}
    >
        {{ $slot }}
    </a>
@else
    <button
        type="{{ $type }}"
        {{ $attributes->class($classes . ' disabled:cursor-not-allowed disabled:opacity-50') }}
    >
        {{ $slot }}
    </button>
@endif
