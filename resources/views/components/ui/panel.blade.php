@props([
    'padding' => 'p-6',
    'soft' => false,
])

<div {{ $attributes->class([
    $soft ? 'fb-panel-soft' : 'fb-panel',
    $padding,
]) }}>
    {{ $slot }}
</div>
