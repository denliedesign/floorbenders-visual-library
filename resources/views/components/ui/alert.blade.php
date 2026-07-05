@props([
    'variant' => 'success',
])

@php
    $classes = match ($variant) {
        'success' => 'border-green-500/30 bg-green-500/10 text-green-200',
        'danger' => 'border-red-500/30 bg-red-500/10 text-red-200',
        'warning' => 'border-amber-500/30 bg-amber-500/10 text-amber-200',
        'info' => 'border-blue-500/30 bg-blue-500/10 text-blue-200',
        default => 'border-stone-700 bg-stone-900 text-stone-200',
    };
@endphp

<div {{ $attributes->class([
    'rounded-xl border px-4 py-3 text-sm',
    $classes,
]) }}>
    {{ $slot }}
</div>
