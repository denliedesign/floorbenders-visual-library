@props([
    'padding' => 'p-6',
    'hover' => false,
])

<div {{ $attributes->class([
    'fb-card',
    'fb-card-hover' => $hover,
    $padding,
]) }}>
    {{ $slot }}
</div>
