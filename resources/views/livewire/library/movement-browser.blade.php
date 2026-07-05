<div class="space-y-8">
    <section class="relative overflow-hidden rounded-3xl border border-stone-800 bg-stone-950 shadow-2xl shadow-black/30">
        <div class="absolute inset-0 opacity-70">
            <div class="absolute -left-24 -top-24 h-72 w-72 rounded-full bg-amber-500/10 blur-3xl"></div>
            <div class="absolute -bottom-32 right-0 h-80 w-80 rounded-full bg-teal-500/10 blur-3xl"></div>
        </div>

        <div class="relative grid gap-8 px-6 py-8 md:grid-cols-[1.35fr_0.65fr] md:px-8 lg:px-10">
            <div>
                <p class="fb-heading-kicker">
                    Movement Atlas
                </p>

                <h1 class="mt-4 max-w-4xl text-4xl font-semibold tracking-tight text-stone-100 sm:text-5xl">
                    Search the core Floorbenders movement library.
                </h1>

                <p class="mt-5 max-w-2xl text-base leading-7 text-stone-400">
                    Browse movement clips organized by gate, aspect, realm, layer, and orientation. Each tile represents one of the core 48 movements from the Floorbenders system.
                </p>

{{--                <div class="mt-6 flex flex-wrap gap-3">--}}
{{--                    <x-ui.badge variant="amber">--}}
{{--                        48 Core Movement Slots--}}
{{--                    </x-ui.badge>--}}

{{--                    <x-ui.badge variant="teal">--}}
{{--                        Real Filmed Clips--}}
{{--                    </x-ui.badge>--}}

{{--                    <x-ui.badge variant="blue">--}}
{{--                        GIF + Video Previews--}}
{{--                    </x-ui.badge>--}}
{{--                </div>--}}
            </div>

{{--            <div class="fb-panel-soft flex flex-col justify-between p-5">--}}
{{--                <div>--}}
{{--                    <p class="text-sm font-medium text-stone-300">--}}
{{--                        Atlas Structure--}}
{{--                    </p>--}}

{{--                    <dl class="mt-4 space-y-3 text-sm">--}}
{{--                        <div class="flex items-center justify-between gap-4">--}}
{{--                            <dt class="text-stone-500">Gates</dt>--}}
{{--                            <dd class="font-medium text-stone-200">{{ collect($gates)->map->label()->implode(' / ') }}</dd>--}}
{{--                        </div>--}}

{{--                        <div class="flex items-center justify-between gap-4">--}}
{{--                            <dt class="text-stone-500">Aspects</dt>--}}
{{--                            <dd class="font-medium text-stone-200">Sky / Earth</dd>--}}
{{--                        </div>--}}

{{--                        <div class="flex items-center justify-between gap-4">--}}
{{--                            <dt class="text-stone-500">Realms</dt>--}}
{{--                            <dd class="font-medium text-stone-200">6 movement realms</dd>--}}
{{--                        </div>--}}

{{--                        <div class="flex items-center justify-between gap-4">--}}
{{--                            <dt class="text-stone-500">Formula</dt>--}}
{{--                            <dd class="font-medium text-amber-300">{{ count($gates) }} × {{ count($aspects) }} × {{ count($realms) }}</dd>--}}
{{--                        </div>--}}
{{--                    </dl>--}}
{{--                </div>--}}

{{--                <div class="mt-6 rounded-2xl border border-amber-500/20 bg-amber-500/10 p-4">--}}
{{--                    <p class="text-sm text-amber-100">--}}
{{--                        Use the filters below to explore how each movement fits inside the larger Floorbenders map.--}}
{{--                    </p>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
    </section>

    <x-floorbenders.taxonomy-mini-key />

    <section class="relative overflow-hidden rounded-3xl border border-stone-800 bg-stone-900/70 p-6 shadow-xl shadow-black/20">
        <div class="absolute right-0 top-0 h-72 w-72 translate-x-20 -translate-y-20 rounded-full bg-amber-500/10 blur-3xl"></div>

        <div class="relative grid gap-8 lg:grid-cols-[0.8fr_1.2fr]">
            <div>
                <p class="fb-heading-kicker">
                    Taxonomy Map
                </p>

                <h2 class="mt-3 text-3xl font-semibold tracking-tight text-stone-100">
                    Every movement belongs somewhere in the atlas.
                </h2>

                <p class="mt-4 text-sm leading-7 text-stone-400">
                    Every movement in floorbenders belongs in a neatly ordered system of categorized groups and classifications. The badge system gives each movement a readable identity.
                </p>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <p class="fb-heading-kicker mb-3 text-sm font-medium text-teal-300">
                        Realms
                    </p>

                    <div class="flex flex-wrap items-center gap-3">
                        @foreach (\App\Enums\Realm::cases() as $realm)
                            <x-floorbenders.realm-badge :realm="$realm" show-label size="sm" />
                        @endforeach
                    </div>
                </div>

                <div>
                    <p class="fb-heading-kicker mb-3 text-sm font-medium text-teal-300">
                        Gates & Aspects
                    </p>

                    <div class="flex flex-wrap items-center gap-3">
                        @foreach (\App\Enums\Gate::cases() as $gate)
                            <x-floorbenders.gate-badge :gate="$gate" show-label size="sm" />
                        @endforeach

                        @foreach (\App\Enums\Aspect::cases() as $aspect)
                            <x-floorbenders.aspect-badge :aspect="$aspect" show-label size="sm" />
                        @endforeach
                    </div>
                </div>

                <div>
                    <p class="fb-heading-kicker mb-3 text-sm font-medium text-teal-300">
                        Layers
                    </p>

                    <div class="flex flex-wrap items-center gap-3">
                        @foreach (\App\Enums\RealmLayer::cases() as $layer)
                            <x-floorbenders.layer-badge :layer="$layer" show-label size="sm" />
                        @endforeach
                    </div>
                </div>

                <div>
                    <p class="fb-heading-kicker mb-3 text-sm font-medium text-teal-300">
                        Orientations
                    </p>

                    <div class="flex flex-wrap items-center gap-3">
                        @foreach (\App\Enums\RealmOrientation::cases() as $orientation)
                            <x-floorbenders.orientation-badge :orientation="$orientation" show-label size="sm" />
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <x-ui.panel>
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <x-ui.section-heading
                title="Realm Filters"
                description="Narrow the atlas by movement category, body pathway, or difficulty."
            />

            @if ($this->hasActiveFilters)
                <x-ui.button variant="secondary" wire:click="clearFilters">
                    Clear Filters
                </x-ui.button>
            @endif
        </div>

        <div class="mt-6 grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            <x-ui.input
                name="search"
                label="Search"
                type="search"
                wire:model.live.debounce.300ms="search"
                placeholder="Search title, position, notes..."
            />

            <x-ui.select name="gate" label="Gate" wire:model.live="gate">
                <option value="">All Gates</option>

                @foreach ($gates as $gateOption)
                    <option value="{{ $gateOption->value }}">
                        {{ $gateOption->label() }}
                    </option>
                @endforeach
            </x-ui.select>

            <x-ui.select name="aspect" label="Aspect" wire:model.live="aspect">
                <option value="">All Aspects</option>

                @foreach ($aspects as $aspectOption)
                    <option value="{{ $aspectOption->value }}">
                        {{ $aspectOption->label() }}
                    </option>
                @endforeach
            </x-ui.select>

            <x-ui.select name="realm" label="Realm" wire:model.live="realm">
                <option value="">All Realms</option>

                @foreach ($realms as $realmOption)
                    <option value="{{ $realmOption->value }}">
                        {{ $realmOption->label() }}
                    </option>
                @endforeach
            </x-ui.select>

            <x-ui.select name="orientation" label="Orientation" wire:model.live="orientation">
                <option value="">All Orientations</option>

                @foreach ($orientations as $orientationOption)
                    <option value="{{ $orientationOption->value }}">
                        {{ $orientationOption->label() }}
                    </option>
                @endforeach
            </x-ui.select>

            <x-ui.select name="layer" label="Layer" wire:model.live="layer">
                <option value="">All Layers</option>

                @foreach ($layers as $layerOption)
                    <option value="{{ $layerOption->value }}">
                        {{ $layerOption->label() }}
                    </option>
                @endforeach
            </x-ui.select>

            <x-ui.select name="difficulty" label="Difficulty" wire:model.live="difficulty">
                <option value="">All Difficulties</option>

                @foreach ($difficulties as $difficultyOption)
                    <option value="{{ $difficultyOption->value }}">
                        {{ $difficultyOption->label() }}
                    </option>
                @endforeach
            </x-ui.select>

            <div class="rounded-xl border border-stone-800 bg-stone-950/60 p-4">
                <p class="text-sm font-medium text-stone-300">
                    Results
                </p>

                <p class="mt-1 text-2xl font-semibold text-stone-100">
                    {{ $movements->total() }}
                </p>
            </div>
        </div>
    </x-ui.panel>

    <section class="space-y-5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="fb-heading-kicker">
                    Atlas Tiles
                </p>

                <h2 class="mt-2 text-2xl font-semibold tracking-tight text-stone-100">
                    Published Movements
                </h2>
            </div>

            <p class="text-sm text-stone-500">
                Showing {{ $movements->firstItem() ?? 0 }}–{{ $movements->lastItem() ?? 0 }}
                of {{ $movements->total() }}
            </p>
        </div>

        @if ($movements->count())
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($movements as $movement)
                    <article class="group overflow-hidden rounded-3xl border border-stone-800 bg-stone-900/70 shadow-xl shadow-black/20 transition hover:-translate-y-1 hover:border-amber-500/40 hover:shadow-amber-950/20">
                        <a
                            href="{{ route('library.show', $movement) }}"
                            wire:navigate
                            class="block"
                        >
                            <div class="relative aspect-video overflow-hidden bg-stone-950">
                                @if ($movement->mediaAsset?->gif_path)
                                    <img
                                        src="{{ $movement->mediaAsset->gifUrl() }}"
                                        alt="Preview of {{ $movement->title }}"
                                        class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                        loading="lazy"
                                    >
                                @elseif ($movement->mediaAsset?->thumbnail_path)
                                    <img
                                        src="{{ $movement->mediaAsset->thumbnailUrl() }}"
                                        alt="Thumbnail of {{ $movement->title }}"
                                        class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                        loading="lazy"
                                    >
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-sm text-stone-600">
                                        No preview
                                    </div>
                                @endif

                                <div class="absolute left-4 top-4">
                                    <x-floorbenders.realm-badge :realm="$movement->realm" size="sm" />
                                </div>

                                <div class="absolute bottom-4 right-4 rounded-full border border-stone-700 bg-stone-950/80 px-3 py-1 text-xs font-medium text-stone-200 backdrop-blur">
                                    {{ $movement->difficulty->label() }}
                                </div>
                            </div>

                            <div class="space-y-4 p-5">
                                <div>
                                    <div class="flex items-start justify-between gap-3">
                                        <h3 class="text-lg font-semibold tracking-tight text-stone-100 group-hover:text-amber-300">
                                            {{ $movement->title }}
                                        </h3>

                                        <x-floorbenders.note-badge :movement="$movement" />
                                    </div>

                                    <p class="mt-2 text-sm text-stone-500">
                                        {{ $movement->start_facing->label() }}
                                        <span class="text-stone-700">→</span>
                                        {{ $movement->end_facing->label() }}
                                    </p>
                                </div>

                                <x-floorbenders.taxonomy-row :movement="$movement" compact />

                                <div class="grid grid-cols-2 gap-3 text-xs">
                                    <div class="rounded-2xl border border-stone-800 bg-stone-950/50 p-3">
                                        <p class="text-stone-500">
                                            Orientation
                                        </p>

                                        <p class="mt-1 font-medium text-stone-200">
                                            {{ $movement->realm->orientation()->label() }}
                                        </p>
                                    </div>

                                    <div class="rounded-2xl border border-stone-800 bg-stone-950/50 p-3">
                                        <p class="text-stone-500">
                                            Layer
                                        </p>

                                        <p class="mt-1 font-medium text-stone-200">
                                            {{ $movement->realm->layer()->label() }}
                                        </p>
                                    </div>
                                </div>

                                @if ($movement->description)
                                    <p class="text-sm leading-6 text-stone-400">
                                        {{ str($movement->description)->limit(120) }}
                                    </p>
                                @endif
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>

            <div>
                {{ $movements->links() }}
            </div>
        @else
            <x-ui.empty-state
                title="No published movements found."
                description="Try clearing your filters, or process and publish movement clips from the admin area."
            >
                <x-slot:actions>
                    @if ($this->hasActiveFilters)
                        <x-ui.button wire:click="clearFilters">
                            Clear Filters
                        </x-ui.button>
                    @endif
                </x-slot:actions>
            </x-ui.empty-state>
        @endif
    </section>
</div>
