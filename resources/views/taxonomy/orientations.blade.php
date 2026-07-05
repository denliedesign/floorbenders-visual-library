@php
    $orientations = \App\Enums\RealmOrientation::cases();
@endphp

<x-layouts.public title="Orientation">
    <div class="space-y-8">
        <section class="fb-panel p-5">
            <x-floorbenders.taxonomy-page-nav />

            <div class="mt-8">
                <p class="fb-heading-kicker">
                    Floorbenders Taxonomy
                </p>

                <h1 class="mt-3 text-4xl font-semibold tracking-tight text-stone-100 md:text-5xl">
                    Orientation
                </h1>

                <p class="mt-5 max-w-4xl text-base leading-7 text-stone-400">
                    Orientation describes whether the body is horizontal to the ground or perpendicular to the ground.
                    It gives a simple structural reading of the six realms.
                </p>
            </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-2">
            @foreach ($orientations as $orientation)
                @php
                    $orientationRealms = \App\Enums\Realm::forOrientation($orientation);
                @endphp

                <article class="fb-panel p-5">
                    <div class="flex items-center gap-4">
                        <x-floorbenders.orientation-badge :orientation="$orientation" />

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-teal-300">
                                Orientation
                            </p>

                            <h2 class="mt-1 text-2xl font-semibold text-stone-100">
                                {{ $orientation->label() }}
                            </h2>
                        </div>
                    </div>

                    <p class="mt-5 text-sm leading-7 text-stone-400">
                        @if ($orientation->value === 'horizontal')
                            Horizontal realms relate the body more parallel to the ground. These positions tend to read across
                            the floor plane.
                        @else
                            Vertical realms relate the body more perpendicular to the ground. These positions rise more upright
                            or inverted against the floor plane.
                        @endif
                    </p>

                    <div class="mt-6 grid gap-3">
                        @foreach ($orientationRealms as $realm)
                            <div class="flex items-center justify-between gap-4 rounded-2xl border border-stone-800 bg-stone-950/70 p-4">
                                <div class="flex items-center gap-3">
                                    <x-floorbenders.realm-badge :realm="$realm" />

                                    <div>
                                        <p class="font-semibold text-stone-100">
                                            {{ $realm->label() }}
                                        </p>

                                        <p class="mt-1 text-xs text-stone-500">
                                            {{ $realm->layer()->label() }} layer
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </article>
            @endforeach
        </section>

        <section class="fb-panel p-5">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-teal-300">
                Pattern reading
            </p>

            <h2 class="mt-3 text-2xl font-semibold text-stone-100">
                Orientation helps you see whether a phrase stays low and across, or rises into vertical structures.
            </h2>

            <div class="mt-6 grid gap-4 lg:grid-cols-3">
                <div class="rounded-3xl border border-stone-800 bg-stone-950/70 p-5">
                    <p class="text-lg font-semibold text-stone-100">
                        do → mi → so
                    </p>

                    <p class="mt-3 text-sm leading-6 text-stone-400">
                        Sky Insect → Sky Mammal → Sky Bird. This is all horizontal.
                    </p>
                </div>

                <div class="rounded-3xl border border-stone-800 bg-stone-950/70 p-5">
                    <p class="text-lg font-semibold text-stone-100">
                        re → fa → la
                    </p>

                    <p class="mt-3 text-sm leading-6 text-stone-400">
                        Sky Reptile → Sky Amphibian → Sky Fish. This is all vertical.
                    </p>
                </div>

                <div class="rounded-3xl border border-stone-800 bg-stone-950/70 p-5">
                    <p class="text-lg font-semibold text-stone-100">
                        do → re → mi
                    </p>

                    <p class="mt-3 text-sm leading-6 text-stone-400">
                        This crosses from horizontal to vertical and back to horizontal.
                    </p>
                </div>
            </div>
        </section>
    </div>
</x-layouts.public>
