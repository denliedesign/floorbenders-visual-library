@props([
    'title',
    'description' => null,
])

<div {{ $attributes }}>
    <h2 class="text-xl font-semibold tracking-tight text-stone-100">
        {{ $title }}
    </h2>

    @if ($description)
        <p class="mt-1 text-sm text-stone-400">
            {{ $description }}
        </p>
    @endif
</div>
