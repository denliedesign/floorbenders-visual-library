@props([
    'title',
    'description' => null,
])

<div {{ $attributes->class('rounded-2xl border border-dashed border-stone-700 bg-stone-900/40 p-10 text-center') }}>
    <h3 class="font-semibold text-stone-100">
        {{ $title }}
    </h3>

    @if ($description)
        <p class="mx-auto mt-2 max-w-md text-sm text-stone-400">
            {{ $description }}
        </p>
    @endif

    @isset($actions)
        <div class="mt-5 flex justify-center gap-3">
            {{ $actions }}
        </div>
    @endisset
</div>
