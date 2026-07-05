@props([
    'variant' => 'default',
])

@php
    $variantClass = match ($variant) {
        'amber' => 'fb-badge-amber',
        'teal' => 'fb-badge-teal',
        'blue' => 'fb-badge-blue',
        'green' => 'fb-badge-green',
        'rust' => 'fb-badge-rust',
        'slate' => 'fb-badge-slate',
        'danger' => 'border-red-500/40 bg-red-500/10 text-red-200',
        'red' => 'border-red-500/40 bg-red-500/10 text-red-200',
        default => '',
    };
@endphp

<span {{ $attributes->merge([
    'class' => trim('fb-badge ' . $variantClass),
]) }}>
    {{ $slot }}
</span>
