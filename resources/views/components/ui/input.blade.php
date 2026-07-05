@props([
    'label' => null,
    'name' => null,
    'type' => 'text',
    'error' => null,
])

<div>
    @if ($label)
        <label
            @if ($name) for="{{ $name }}" @endif
        class="mb-1 block text-sm font-medium text-stone-200"
        >
            {{ $label }}
        </label>
    @endif

    <input
        @if ($name) id="{{ $name }}" @endif
    type="{{ $type }}"
        {{ $attributes->class('fb-input') }}
    >

    @if ($error)
        <p class="mt-1 text-sm text-red-300">{{ $error }}</p>
    @endif
</div>
