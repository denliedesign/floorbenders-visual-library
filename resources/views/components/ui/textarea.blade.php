@props([
    'label' => null,
    'name' => null,
    'error' => null,
    'rows' => 4,
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

    <textarea
        @if ($name) id="{{ $name }}" @endif
    rows="{{ $rows }}"
        {{ $attributes->class('fb-input') }}
    >{{ $slot }}</textarea>

    @if ($error)
        <p class="mt-1 text-sm text-red-300">{{ $error }}</p>
    @endif
</div>
