@php
    $layers = \App\Enums\RealmLayer::cases();
@endphp

<x-layouts.public title="Layers">
    <div class="space-y-8">
        <section class="fb-panel p-5">
            <x-floorbenders.taxonomy-page-nav />

            <div class="mt-8">
                <p class="fb-heading-kicker">
                    Floorbenders Taxonomy
                </p>

                <h1 class="mt-3 text-4xl font-semibold tracking-tight text-stone-100 md:text-5xl">
                    Layers
                </h1>

                <p class="mt-5 max-w-4xl text-base leading-7 text-stone-400">
                    Layers group the six realms by the mover’s relationship to the floor: body grounded, body lifted,
                    or the body supported unconventionally.
                </p>
            </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-3">
            @foreach ($layers as $layer)
                @php
                    $layerRealms = \App\Enums\Realm::forLayer($layer);
                @endphp

                <article class="fb-panel p-5">
                    <div class="flex items-center gap-4">
                        <x-floorbenders.layer-badge :layer="$layer" />

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-orange-300">
                                Layer
                            </p>

                            <h2 class="mt-1 text-2xl font-semibold text-stone-100">
                                {{ $layer->label() }}
                            </h2>
                        </div>
                    </div>

                    <p class="mt-5 text-sm leading-7 text-stone-400">
                        @if ($layer->value === 'grounded')
                            {{ $layer->description() }}
                        @elseif ($layer->value === 'lifted')
                            {{ $layer->description() }}
                        @else
                            {{ $layer->description() }}
                        @endif
                    </p>

                    <div class="mt-6 space-y-3">
                        @foreach ($layerRealms as $realm)
                            <div class="flex items-center gap-3 rounded-2xl border border-stone-800 bg-stone-950/70 p-4">
                                <x-floorbenders.realm-badge :realm="$realm" />

                                <div>
                                    <p class="font-semibold text-stone-100">
                                        {{ $realm->label() }}
                                    </p>

                                    <p class="mt-1 text-xs text-stone-500">
                                        {{ $realm->orientation()->label() }} orientation
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </article>
            @endforeach
        </section>

        <section class="fb-panel p-5">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-orange-300">
                How layers reveal phrase architecture
            </p>

            <h2 class="mt-3 text-2xl font-semibold text-stone-100">
                A phrase can travel through the three layers like a chord.
            </h2>

            <div class="mt-6 grid gap-4 lg:grid-cols-3">
                <div class="rounded-3xl border border-stone-800 bg-stone-950/70 p-5">
                    <p class="text-lg font-semibold text-stone-100">
                        do
                    </p>

                    <p class="mt-3 text-sm leading-6 text-stone-400">
                        Sky Insect. Horizontal and grounded.
                    </p>
                </div>

                <div class="rounded-3xl border border-stone-800 bg-stone-950/70 p-5">
                    <p class="text-lg font-semibold text-stone-100">
                        mi
                    </p>

                    <p class="mt-3 text-sm leading-6 text-stone-400">
                        Sky Mammal. Horizontal and lifted.
                    </p>
                </div>

                <div class="rounded-3xl border border-stone-800 bg-stone-950/70 p-5">
                    <p class="text-lg font-semibold text-stone-100">
                        so
                    </p>

                    <p class="mt-3 text-sm leading-6 text-stone-400">
                        Sky Bird. Horizontal and supported.
                    </p>
                </div>
            </div>

            <p class="mt-6 text-sm leading-7 text-stone-400">
                Together, do → mi → so reveals a phrase that is all Sky and all Horizontal, but travels through all three layers:
                Grounded → Lifted → Supported.
            </p>
        </section>
    </div>
</x-layouts.public>
