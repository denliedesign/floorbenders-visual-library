@php
    $aspects = \App\Enums\Aspect::cases();
@endphp

<x-layouts.public title="Aspects">
    <div class="space-y-8">
        <section class="fb-panel p-5">
            <x-floorbenders.taxonomy-page-nav />

            <div class="mt-8">
                <p class="fb-heading-kicker">
                    Floorbenders Taxonomy
                </p>

                <h1 class="mt-3 text-4xl font-semibold tracking-tight text-stone-100 md:text-5xl">
                    Aspects
                </h1>

                <p class="mt-5 max-w-4xl text-base leading-7 text-stone-400">
                    Aspect describes whether the head and face are oriented toward the sky above or the earth below.
                    Every realm has both a Sky and Earth version, which makes aspect one of the clearest ways to see contrast
                    inside the system.
                </p>
            </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-2">
            @foreach ($aspects as $aspect)
                <article class="fb-panel p-5">
                    <div class="flex items-center gap-4">
                        <x-floorbenders.aspect-badge :aspect="$aspect" />

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-300">
                                Aspect
                            </p>

                            <h2 class="mt-1 text-2xl font-semibold text-stone-100">
                                {{ $aspect->label() }}
                            </h2>
                        </div>
                    </div>

                    <p class="mt-5 text-sm leading-7 text-stone-400">
                        @if ($aspect->value === 'sky')
                            Sky means the head or face is oriented toward the sky above. In the note system, Sky acts like
                            the white-key side of the atlas: do, re, mi, fa, so, la.
                        @else
                            Earth means the head or face is oriented toward the earth below. In the note system, Earth acts
                            like the black-key side of the atlas: di, ri, my, fi, si, li.
                        @endif
                    </p>
                </article>
            @endforeach
        </section>

        <section class="fb-panel p-5">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-amber-300">
                Aspect + Realm = Note
            </p>

            <h2 class="mt-3 text-2xl font-semibold text-stone-100">
                Every realm has an aspect
            </h2>

            <p class="mt-4 max-w-4xl text-sm leading-7 text-stone-400">
                All six realm positions are oriented either toward the sky or the earth. Aspect and realm together create an atlas note:
                Sky Insect becomes do, Earth Insect becomes di, Sky Mammal becomes mi, Earth Mammal becomes my, etc.
            </p>

            <div class="mt-6 overflow-x-auto">
                <x-floorbenders.note-map />
            </div>
        </section>

        <section class="fb-panel p-5">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-300">
                Example
            </p>

            <h2 class="mt-3 text-2xl font-semibold text-stone-100">
                Same realm, different aspect.
            </h2>

            <div class="mt-6 grid gap-4 md:grid-cols-2">
                <div class="rounded-3xl border border-blue-400/20 bg-blue-400/10 p-5">
                    <p class="text-lg font-semibold text-blue-100">
                        Sky Insect = do
                    </p>

                    <p class="mt-3 text-sm leading-6 text-blue-100/70">
                        The mover is in the Insect realm with the head and face oriented toward the sky in a supine position.
                    </p>
                </div>

                <div class="rounded-3xl border border-amber-400/20 bg-amber-400/10 p-5">
                    <p class="text-lg font-semibold text-amber-100">
                        Earth Insect = di
                    </p>

                    <p class="mt-3 text-sm leading-6 text-amber-100/70">
                        The mover is still in the Insect realm, but the head and face are oriented toward the earth in a prone position.
                    </p>
                </div>
            </div>
        </section>
    </div>
</x-layouts.public>
