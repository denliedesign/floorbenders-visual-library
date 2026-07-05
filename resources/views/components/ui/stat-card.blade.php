@props([
    'label',
    'value',
    'description' => null,
    'variant' => 'amber',
])

<x-ui.card padding="p-5">
    <p class="text-sm text-stone-400">
        {{ $label }}
    </p>

    <p class="mt-3 text-3xl font-semibold text-stone-100">
        {{ $value }}
    </p>

    @if ($description)
        <p class="mt-2 text-sm text-stone-500">
            {{ $description }}
        </p>
    @endif
</x-ui.card>
