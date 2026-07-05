@php
    $realms = \App\Enums\Realm::cases();

    $noteFor = function (string $aspect, string $realm): string {
        return match ($aspect . ':' . $realm) {
            'sky:insect' => 'do',
            'earth:insect' => 'di',
            'sky:reptile' => 're',
            'earth:reptile' => 'ri',
            'sky:mammal' => 'mi',
            'earth:mammal' => 'my',
            'sky:amphibian' => 'fa',
            'earth:amphibian' => 'fi',
            'sky:bird' => 'so',
            'earth:bird' => 'si',
            'sky:fish' => 'la',
            'earth:fish' => 'li',
            default => '—',
        };
    };
@endphp

<x-layouts.public title="Realms">
    <div class="space-y-8">
        <section class="fb-panel p-5">
            <x-floorbenders.taxonomy-page-nav />

            <div class="mt-8">
                <p class="fb-heading-kicker">
                    Floorbenders Taxonomy
                </p>

                <h1 class="mt-3 text-4xl font-semibold tracking-tight text-stone-100 md:text-5xl">
                    Realms
                </h1>

                <p class="mt-5 max-w-4xl text-base leading-7 text-stone-400">
                    Realms are the six core positions of Floorbenders. They are the main body-position families used to organize
                    the atlas. Every realm belongs to an orientation, belongs to a layer, and has both Sky and Earth note forms.
                </p>
            </div>
        </section>

        <section class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($realms as $realm)
                <article class="fb-panel p-5">
                    <div class="flex items-center gap-4">
                        <x-floorbenders.realm-badge :realm="$realm" />

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-green-300">
                                Realm
                            </p>

                            <h2 class="mt-1 text-2xl font-semibold text-stone-100">
                                {{ $realm->label() }}
                            </h2>
                        </div>
                    </div>

                    <div class="mt-5 flex flex-wrap gap-2">
                        <x-ui.badge variant="teal">
                            {{ $realm->orientation()->label() }}
                        </x-ui.badge>

                        <x-ui.badge variant="amber">
                            {{ $realm->layer()->label() }}
                        </x-ui.badge>

                        <x-ui.badge variant="blue">
                            Sky: {{ $noteFor('sky', $realm->value) }}
                        </x-ui.badge>

                        <x-ui.badge variant="rust">
                            Earth: {{ $noteFor('earth', $realm->value) }}
                        </x-ui.badge>
                    </div>

                    <p class="mt-5 text-sm leading-7 text-stone-400">
                        @if (method_exists($realm, 'description'))
                            {{ $realm->description() }}
                        @else
                            {{ $realm->label() }} is one of the six core position families in the atlas.
                        @endif
                    </p>
                </article>
            @endforeach
        </section>

        <section class="fb-panel p-5">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-green-300">
                How realms connect
            </p>

            <h2 class="mt-3 text-2xl font-semibold text-stone-100">
                Realms sit at the center of the taxonomy.
            </h2>

            <div class="mt-6 grid gap-4 lg:grid-cols-4">
                <div class="rounded-3xl border border-stone-800 bg-stone-950/70 p-5">
                    <p class="font-semibold text-stone-100">
                        Realm
                    </p>

                    <p class="mt-3 text-sm leading-6 text-stone-400">
                        The core position family.
                    </p>
                </div>

                <div class="rounded-3xl border border-stone-800 bg-stone-950/70 p-5">
                    <p class="font-semibold text-stone-100">
                        Orientation
                    </p>

                    <p class="mt-3 text-sm leading-6 text-stone-400">
                        Whether that realm is horizontal or vertical.
                    </p>
                </div>

                <div class="rounded-3xl border border-stone-800 bg-stone-950/70 p-5">
                    <p class="font-semibold text-stone-100">
                        Layer
                    </p>

                    <p class="mt-3 text-sm leading-6 text-stone-400">
                        Whether the body is grounded, lifted, or unconventionally supported.
                    </p>
                </div>

                <div class="rounded-3xl border border-stone-800 bg-stone-950/70 p-5">
                    <p class="font-semibold text-stone-100">
                        Note
                    </p>

                    <p class="mt-3 text-sm leading-6 text-stone-400">
                        The shorthand created when realm combines with Sky or Earth aspect.
                    </p>
                </div>
            </div>
        </section>
    </div>
</x-layouts.public>
