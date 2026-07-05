@props([
    'kicker' => null,
    'title',
    'description' => null,
])

<div {{ $attributes->class('flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between') }}>
    <div>
        @if ($kicker)
            <p class="fb-heading-kicker">
                {{ $kicker }}
            </p>
        @endif

        <h1 class="mt-2 text-3xl font-semibold tracking-tight text-stone-100 sm:text-4xl">
            {{ $title }}
        </h1>

        @if ($description)
            <p class="mt-3 max-w-3xl text-stone-400">
                {{ $description }}
            </p>
        @endif
    </div>

    @isset($actions)
        <div class="flex flex-wrap gap-3">
            {{ $actions }}
        </div>
    @endisset
</div>
