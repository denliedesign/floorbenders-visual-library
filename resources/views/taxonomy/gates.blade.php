@php
    $gates = \App\Enums\Gate::cases();
@endphp

<x-layouts.public title="Gates">
    <div class="space-y-8">
        <section class="fb-panel p-5">
            <x-floorbenders.taxonomy-page-nav />

            <div class="mt-8">
                <p class="fb-heading-kicker">
                    Floorbenders Taxonomy
                </p>

                <h1 class="mt-3 text-4xl font-semibold tracking-tight text-stone-100 md:text-5xl">
                    Gates
                </h1>

                <p class="mt-5 max-w-4xl text-base leading-7 text-stone-400">
                    A gate is the transitional pathway the mover takes between realms. In Floorbenders, the realm tells you
                    what position family the mover is in, while the gate describes the path used to move from one realm to another.
                </p>
            </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-[0.8fr_1.2fr]">
            <div class="fb-panel p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-amber-400">
                    Why gates matter
                </p>

                <h2 class="mt-3 text-2xl font-semibold text-stone-100">
                    Gates organize transition logic.
                </h2>

                <div class="mt-5 space-y-4 text-sm leading-7 text-stone-400">
                    <p>
                        Without gates, a movement atlas becomes a loose collection of positions. Gates turn those positions
                        into pathways. They answer the question: how does the mover travel between realms?
                    </p>

                    <p>
                        Most phrases stay within one gate because the pathway itself becomes the organizing idea. The mover
                        can still change aspect, realm, layer, and orientation while keeping the same transitional logic.
                    </p>
                    <p>
                        Because the gate remains consistent, it becomes the connective tissue of the phrase. The mover can treat the gate like a home base, using it to travel seamlessly into any realm and then return. In this way, Floorbenders improvisation works a bit like jazz: the motif gives the mover something to come back to, while still leaving room to explore, vary, and play.
                    </p>
                </div>
            </div>

            <div class="fb-panel p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-amber-400">
                    Gate options
                </p>

                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    @foreach ($gates as $gate)
                        <article class="rounded-3xl border border-stone-800 bg-stone-950/70 p-5">
                            <div class="flex items-center gap-3">
                                <x-floorbenders.gate-badge :gate="$gate" />

                                <div>
                                    <h3 class="font-semibold text-stone-100">
                                        {{ $gate->label() }}
                                    </h3>

                                    <p class="mt-1 text-xs text-stone-500">
                                        {{ method_exists($gate, 'shortLabel') ? $gate->shortLabel() : $gate->value }}
                                    </p>
                                </div>
                            </div>

                            @if (method_exists($gate, 'description'))
                                <p class="mt-4 text-sm leading-6 text-stone-400">
                                    {{ $gate->description() }}
                                </p>
                            @else
                                <p class="mt-4 text-sm leading-6 text-stone-400">
                                    A transitional pathway used to travel between realm positions.
                                </p>
                            @endif
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="fb-panel p-5">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-amber-400">
                Example
            </p>

            <h2 class="mt-3 text-2xl font-semibold text-stone-100">
                A phrase can stay in one gate while changing everything else.
            </h2>

            <div class="mt-6 grid gap-4 lg:grid-cols-3">
                <div class="rounded-3xl border border-stone-800 bg-stone-950/70 p-5">
                    <p class="text-sm font-semibold text-stone-100">
                        Same gate
                    </p>

                    <p class="mt-3 text-sm leading-6 text-stone-400">
                        The phrase follows one transitional pathway from step to step.
                    </p>
                </div>

                <div class="rounded-3xl border border-stone-800 bg-stone-950/70 p-5">
                    <p class="text-sm font-semibold text-stone-100">
                        Changing realms
                    </p>

                    <p class="mt-3 text-sm leading-6 text-stone-400">
                        The mover may pass through Insect, Mammal, Bird, Reptile, or other realm families.
                    </p>
                </div>

                <div class="rounded-3xl border border-stone-800 bg-stone-950/70 p-5">
                    <p class="text-sm font-semibold text-stone-100">
                        Changing notes
                    </p>

                    <p class="mt-3 text-sm leading-6 text-stone-400">
                        The phrase might read as do → mi → so while still belonging to one gate pathway.
                    </p>
                </div>
            </div>
        </section>
    </div>
</x-layouts.public>
