<div class="space-y-8">
    <x-ui.page-header
        kicker="Admin Console"
        title="Movement Management"
        description="Manage the 48 core Floorbenders movement slots, review taxonomy data, and jump into media processing."
    >
        <x-slot:actions>
            <x-ui.button :href="route('library.index')" variant="secondary" navigate>
                View Public Atlas
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    @if (session('status'))
        <x-ui.alert>
            {{ session('status') }}
        </x-ui.alert>
    @endif

    <x-ui.panel>
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <x-ui.section-heading
                title="Movement Filters"
                description="Find movement slots by title, gate, aspect, realm, or publishing status."
            />

            <x-ui.button variant="secondary" wire:click="clearFilters">
                Clear Filters
            </x-ui.button>
        </div>

        <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-5">
            <x-ui.input
                name="search"
                label="Search"
                type="search"
                wire:model.live.debounce.300ms="search"
                placeholder="Search movement title..."
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

            <x-ui.select name="status" label="Status" wire:model.live="status">
                <option value="">All Statuses</option>

                @foreach ($statuses as $statusOption)
                    <option value="{{ $statusOption->value }}">
                        {{ $statusOption->label() }}
                    </option>
                @endforeach
            </x-ui.select>
        </div>
    </x-ui.panel>

    <section class="space-y-5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="fb-heading-kicker">
                    Core Slots
                </p>

                <h2 class="mt-2 text-2xl font-semibold tracking-tight text-stone-100">
                    24 Movement Pathways
                </h2>
            </div>

            <p class="text-sm text-stone-500">
                Showing {{ $movements->firstItem() ?? 0 }}–{{ $movements->lastItem() ?? 0 }}
                of {{ $movements->total() }}
            </p>
        </div>

        @if ($movements->count())
            <div class="grid gap-5">
                @foreach ($movements as $movement)
                    @php
                        $mediaAsset = $movement->mediaAsset;
                    @endphp

                    <article class="fb-card overflow-hidden">
                        <div class="grid gap-0 lg:grid-cols-[180px_1fr]">
                            <div class="aspect-video bg-stone-950 lg:aspect-auto">
                                @if ($mediaAsset?->thumbnail_path)
                                    <img
                                        src="{{ $mediaAsset->thumbnailUrl() }}"
                                        alt="Thumbnail of {{ $movement->title }}"
                                        class="h-full w-full object-cover"
                                        loading="lazy"
                                    >
                                @elseif ($mediaAsset?->gif_path)
                                    <img
                                        src="{{ $mediaAsset->gifUrl() }}"
                                        alt="Preview of {{ $movement->title }}"
                                        class="h-full w-full object-cover"
                                        loading="lazy"
                                    >
                                @else
                                    <div class="flex h-full min-h-32 items-center justify-center text-sm text-stone-600">
                                        No media
                                    </div>
                                @endif
                            </div>

                            <div class="space-y-5 p-5">
                                <div class="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-3">
                                            <h3 class="text-xl font-semibold tracking-tight text-stone-100">
                                                {{ $movement->title }}
                                            </h3>

                                            <x-floorbenders.note-badge :movement="$movement" />
                                            <x-ui.badge variant="{{ $movement->status->value === 'published' ? 'green' : 'slate' }}">
                                                {{ $movement->status->label() }}
                                            </x-ui.badge>

                                            @if ($mediaAsset)
                                                <x-ui.badge variant="{{ $mediaAsset->processing_status->value === 'complete' ? 'teal' : 'amber' }}">
                                                    Media: {{ str($mediaAsset->processing_status->value)->headline() }}
                                                </x-ui.badge>
                                            @else
                                                <x-ui.badge>
                                                    Media: Missing
                                                </x-ui.badge>
                                            @endif
                                        </div>

                                        <p class="mt-2 text-sm text-stone-500">
                                            {{ $movement->start_facing->label() }}
                                            <span class="text-stone-700">→</span>
                                            {{ $movement->end_facing->label() }}
                                        </p>
                                    </div>

                                    <div class="flex flex-wrap gap-2">
                                        <x-ui.button
                                            :href="route('admin.movements.edit', $movement)"
                                            variant="secondary"
                                            navigate
                                        >
                                            Edit
                                        </x-ui.button>

                                        <x-ui.button
                                            :href="route('admin.movements.media', $movement)"
                                            variant="secondary"
                                            navigate
                                        >
                                            Media
                                        </x-ui.button>

                                        @if ($movement->status->value === 'published' && $mediaAsset?->processing_status->value === 'complete')
                                            <x-ui.button
                                                :href="route('library.show', $movement)"
                                                variant="ghost"
                                                navigate
                                            >
                                                View
                                            </x-ui.button>
                                        @endif
                                    </div>
                                </div>

                                <x-floorbenders.taxonomy-row :movement="$movement" compact />

                                <div class="grid gap-3 text-sm md:grid-cols-4">
                                    <div class="rounded-2xl border border-stone-800 bg-stone-950/50 p-3">
                                        <p class="text-xs text-stone-500">Orientation</p>
                                        <p class="mt-1 font-medium text-stone-200">
                                            {{ $movement->realm->orientation()->label() }}
                                        </p>
                                    </div>

                                    <div class="rounded-2xl border border-stone-800 bg-stone-950/50 p-3">
                                        <p class="text-xs text-stone-500">Layer</p>
                                        <p class="mt-1 font-medium text-stone-200">
                                            {{ $movement->realm->layer()->label() }}
                                        </p>
                                    </div>

                                    <div class="rounded-2xl border border-stone-800 bg-stone-950/50 p-3">
                                        <p class="text-xs text-stone-500">Difficulty</p>
                                        <p class="mt-1 font-medium text-stone-200">
                                            {{ $movement->difficulty->label() }}
                                        </p>
                                    </div>

                                    <div class="rounded-2xl border border-stone-800 bg-stone-950/50 p-3">
                                        <p class="text-xs text-stone-500">Sort Order</p>
                                        <p class="mt-1 font-medium text-stone-200">
                                            {{ $movement->sort_order }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div>
                {{ $movements->links() }}
            </div>
        @else
            <x-ui.empty-state
                title="No movement slots found."
                description="Try clearing filters or checking the seeded 24 core movement records."
            >
                <x-slot:actions>
                    <x-ui.button wire:click="clearFilters">
                        Clear Filters
                    </x-ui.button>
                </x-slot:actions>
            </x-ui.empty-state>
        @endif
    </section>
</div>
