@props([
    'movement',
    'compact' => false,
    'showLayer' => true,
    'showOrientation' => true,
])

@php
    $size = $compact ? 'sm' : 'md';
@endphp

<div {{ $attributes->class([
    'flex flex-wrap items-center gap-2',
    'text-xs' => $compact,
]) }}>
    <x-floorbenders.gate-badge :gate="$movement->gate" :size="$size" />
    <x-floorbenders.aspect-badge :aspect="$movement->aspect" :size="$size" />
    <x-floorbenders.realm-badge :realm="$movement->realm" :size="$size" />

    @if ($showOrientation)
        <x-floorbenders.orientation-badge :orientation="$movement->realm->orientation()" :size="$size" />
    @endif

    @if ($showLayer)
        <x-floorbenders.layer-badge :layer="$movement->realm->layer()" :size="$size" />
    @endif
</div>
