@php
    $gates = \App\Enums\Gate::cases();
    $aspects = \App\Enums\Aspect::cases();
    $realms = \App\Enums\Realm::cases();
    $orientations = \App\Enums\RealmOrientation::cases();
    $layers = \App\Enums\RealmLayer::cases();

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

<section {{ $attributes->class('fb-panel overflow-hidden') }}>
    <div class="flex flex-col gap-6 p-5 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <p class="fb-heading-kicker">
                Floorbenders Taxonomy
            </p>

            <h2 class="mt-3 max-w-4xl text-3xl font-semibold tracking-tight text-stone-100 md:text-4xl">
                A movement language built from pathways, positions, orientations, layers, and notes.
            </h2>

            <p class="mt-5 max-w-4xl text-base leading-7 text-stone-400">
                Floorbenders organizes floorwork into a readable system. Each movement belongs to a gate, aspect, realm,
                orientation, and layer. In phrases, aspect and realm also combine into a note, creating a compact shorthand
                for seeing patterns across the whole atlas.
            </p>
        </div>
    </div>

    <div class="grid gap-6 p-5 xl:grid-cols-2">
        <article class="rounded-3xl border border-stone-800 bg-stone-950/60 p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-amber-400">
                        Gates
                    </p>

                    <h3 class="mt-2 text-2xl font-semibold text-stone-100">
                        Transitional pathways
                    </h3>
                </div>

                <a href="{{ route('taxonomy.gates') }}" class="text-sm font-semibold text-amber-300 hover:text-amber-200">
                    Learn gates →
                </a>
            </div>

            <p class="mt-4 text-sm leading-6 text-stone-400">
                A gate describes the transitional pathway the mover takes between realms. Most phrases stay within one gate,
                which keeps the pathway logic clear while the mover changes realm, aspect, layer, or orientation.
            </p>

            <div class="mt-6 grid gap-3 sm:grid-cols-2">
                @foreach ($gates as $gate)
                    <div class="rounded-2xl border border-stone-800 bg-stone-900/40 p-4">
                        <div class="flex items-center gap-3">
                            <x-floorbenders.gate-badge :gate="$gate" size="md" />

                            <div>
                                <p class="font-semibold text-stone-100">
                                    {{ $gate->label() }}
                                </p>

                                <p class="mt-1 text-xs text-stone-500">
                                    {{ method_exists($gate, 'shortLabel') ? $gate->shortLabel() : $gate->value }}
                                </p>
                            </div>
                        </div>

                        @if (method_exists($gate, 'description'))
                            <p class="mt-3 text-sm leading-6 text-stone-400">
                                {{ $gate->description() }}
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        </article>

        <article class="rounded-3xl border border-stone-800 bg-stone-950/60 p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-300">
                        Aspects
                    </p>

                    <h3 class="mt-2 text-2xl font-semibold text-stone-100">
                        Sky and Earth
                    </h3>
                </div>

                <a href="{{ route('taxonomy.aspects') }}" class="text-sm font-semibold text-blue-300 hover:text-blue-200">
                    Learn aspects →
                </a>
            </div>

            <p class="mt-4 text-sm leading-6 text-stone-400">
                An aspect describes whether the head and face are oriented toward the sky above or the earth below.
                This gives each realm two readable sides, like white-key and altered-key versions of the same position family.
            </p>

            <div class="mt-6 grid gap-3 sm:grid-cols-2">
                @foreach ($aspects as $aspect)
                    <div class="rounded-2xl border border-stone-800 bg-stone-900/40 p-4">
                        <div class="flex items-center gap-3">
                            <x-floorbenders.aspect-badge :aspect="$aspect" size="md" />

                            <div>
                                <p class="font-semibold text-stone-100">
                                    {{ $aspect->label() }}
                                </p>

                                <p class="mt-1 text-xs text-stone-500">
                                    {{ $aspect->value }}
                                </p>
                            </div>
                        </div>

                        @if (method_exists($aspect, 'description'))
                            <p class="mt-3 text-sm leading-6 text-stone-400">
                                {{ $aspect->description() }}
                            </p>
                        @else
                            <p class="mt-3 text-sm leading-6 text-stone-400">
                                {{ $aspect->value === 'sky'
                                    ? 'Head and face are oriented toward the sky above.'
                                    : 'Head and face are oriented toward the earth below.' }}
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        </article>
    </div>

    <div class="m-5 p-5 rounded-3xl border border-stone-800 bg-stone-950/60">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-green-300">
                    Realms
                </p>

                <h3 class="mt-2 text-2xl font-semibold text-stone-100">
                    The six core positions
                </h3>

                <p class="mt-4 max-w-4xl text-sm leading-6 text-stone-400">
                    Realms are the six core position families of the atlas. Each realm has an orientation, a layer,
                    and two aspect-note possibilities.
                </p>
            </div>

            <a href="{{ route('taxonomy.realms') }}" class="text-sm font-semibold text-green-300 hover:text-green-200">
                Learn realms →
            </a>
        </div>

        <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($realms as $realm)
                <div class="rounded-2xl border border-stone-800 bg-stone-900/40 p-5">
                    <div class="flex items-center gap-3">
                        <x-floorbenders.realm-badge :realm="$realm" size="md" />

                        <div>
                            <p class="font-semibold text-stone-100">
                                {{ $realm->label() }}
                            </p>

                            <p class="mt-1 text-xs text-stone-500">
                                {{ $realm->orientation()->label() }} · {{ $realm->layer()->label() }}
                            </p>
                        </div>
                    </div>

                    @if (method_exists($realm, 'description'))
                        <p class="mt-4 text-sm leading-6 text-stone-400">
                            {{ $realm->description() }}
                        </p>
                    @endif

                    <div class="mt-4 flex flex-wrap gap-2">
                        <x-ui.badge variant="blue">
                            Sky: {{ $noteFor('sky', $realm->value) }}
                        </x-ui.badge>

                        <x-ui.badge variant="amber">
                            Earth: {{ $noteFor('earth', $realm->value) }}
                        </x-ui.badge>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="p-5 grid gap-6 xl:grid-cols-2">
        <article class="rounded-3xl border border-stone-800 bg-stone-950/60 p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-teal-300">
                        Orientation
                    </p>

                    <h3 class="mt-2 text-2xl font-semibold text-stone-100">
                        Horizontal or vertical
                    </h3>
                </div>

                <a href="{{ route('taxonomy.orientations') }}" class="text-sm font-semibold text-teal-300 hover:text-teal-200">
                    Learn orientation →
                </a>
            </div>

            <p class="mt-4 text-sm leading-6 text-stone-400">
                Orientation describes whether the body is horizontal to the ground or perpendicular to the ground.
                This helps the atlas show large structural relationships between realms.
            </p>

            <div class="mt-6 grid gap-3 sm:grid-cols-2">
                @foreach ($orientations as $orientation)
                    @php
                        $orientationRealms = \App\Enums\Realm::forOrientation($orientation);
                    @endphp

                    <div class="rounded-2xl border border-stone-800 bg-stone-900/40 p-4">
                        <div class="flex items-center gap-3">
                            <x-floorbenders.orientation-badge :orientation="$orientation" size="md" />

                            <p class="font-semibold text-stone-100">
                                {{ $orientation->label() }}
                            </p>
                        </div>

                        @if (method_exists($orientation, 'description'))
                            <p class="mt-3 text-sm leading-6 text-stone-400">
                                {{ $orientation->description() }}
                            </p>
                        @endif

                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach ($orientationRealms as $realm)
                                <x-ui.badge variant="slate">
                                    {{ $realm->label() }}
                                </x-ui.badge>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </article>

        <article class="rounded-3xl border border-stone-800 bg-stone-950/60 p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-orange-300">
                        Layers
                    </p>

                    <h3 class="mt-2 text-2xl font-semibold text-stone-100">
                        Grounded, lifted, or supported
                    </h3>
                </div>

                <a href="{{ route('taxonomy.layers') }}" class="text-sm font-semibold text-orange-300 hover:text-orange-200">
                    Learn layers →
                </a>
            </div>

            <p class="mt-4 text-sm leading-6 text-stone-400">
                Layers group the six positions into three categories: hips grounded, hips lifted, or the body supported
                unconventionally. This makes the realms easier to compare across levels of contact with the floor.
            </p>

            <div class="mt-6 grid gap-3">
                @foreach ($layers as $layer)
                    @php
                        $layerRealms = \App\Enums\Realm::forLayer($layer);
                    @endphp

                    <div class="rounded-2xl border border-stone-800 bg-stone-900/40 p-4">
                        <div class="flex items-center gap-3">
                            <x-floorbenders.layer-badge :layer="$layer" size="md" />

                            <p class="font-semibold text-stone-100">
                                {{ $layer->label() }}
                            </p>
                        </div>

                        @if (method_exists($layer, 'description'))
                            <p class="mt-3 text-sm leading-6 text-stone-400">
                                {{ $layer->description() }}
                            </p>
                        @endif

                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach ($layerRealms as $realm)
                                <x-ui.badge variant="slate">
                                    {{ $realm->label() }}
                                </x-ui.badge>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </article>
    </div>

    <article class="m-5 p-5 rounded-3xl border border-amber-500/20 bg-amber-500/10 p-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-amber-300">
                    Atlas Notes
                </p>

                <h3 class="mt-2 text-2xl font-semibold text-amber-100">
                    Shorthand for aspect + realm
                </h3>

                <p class="mt-4 max-w-4xl text-sm leading-6 text-amber-100/75">
                    Notes are most useful in phrase building. They let you read a sequence quickly without saying every full
                    taxonomy label. Sky acts like the white-key side of the system, while Earth acts like the black-key side, adapted to Floorbenders so each realm has a paired note.
                </p>
            </div>

            <a href="{{ route('taxonomy.notes') }}" class="text-sm font-semibold text-amber-200 hover:text-amber-100">
                Learn notes →
            </a>
        </div>

        <div class="mt-6 overflow-x-auto">
            <x-floorbenders.note-map />
        </div>

        <div class="mt-6 grid gap-4 lg:grid-cols-3">
            <div class="rounded-2xl border border-amber-500/20 bg-stone-950/60 p-4">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-amber-300">
                    Example
                </p>

                <p class="mt-2 text-lg font-semibold text-amber-100">
                    do → re → mi
                </p>

                <p class="mt-2 text-sm leading-6 text-amber-100/70">
                    Sky Insect → Sky Reptile → Sky Mammal.
                </p>
            </div>

            <div class="rounded-2xl border border-amber-500/20 bg-stone-950/60 p-4">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-amber-300">
                    Chord-like pattern
                </p>

                <p class="mt-2 text-lg font-semibold text-amber-100">
                    do → mi → so
                </p>

                <p class="mt-2 text-sm leading-6 text-amber-100/70">
                    Sky Insect → Sky Mammal → Sky Bird.
                </p>
            </div>

            <div class="rounded-2xl border border-amber-500/20 bg-stone-950/60 p-4">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-amber-300">
                    What it reveals
                </p>

                <p class="mt-2 text-sm leading-6 text-amber-100/70">
                    The Chord-like phrase is all Sky, all Horizontal, and travels across all three layers: Grounded, Lifted, Supported.
                </p>
            </div>
        </div>
    </article>
</section>
