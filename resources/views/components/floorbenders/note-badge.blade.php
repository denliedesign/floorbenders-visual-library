@props([
    'movement',
    'label' => false,
])

<x-ui.badge variant="amber" {{ $attributes }}>
    @if ($label)
        <span class="text-amber-100/70">Note:</span>
    @endif

    <span>{{ $movement->atlasNote() }}</span>
</x-ui.badge>
