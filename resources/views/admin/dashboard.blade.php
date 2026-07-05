<x-layouts.admin :title="__('Admin Dashboard')">
    <div class="space-y-8">
        <x-ui.page-header
            kicker="Admin Console"
            title="Floorbenders Command Center"
            description="Track the atlas, media processing, published movement tiles, and phrase builder progress from one place."
        >
            <x-slot:actions>
                <x-ui.button :href="route('library.index')" variant="secondary" navigate>
                    View Public Atlas
                </x-ui.button>

                <x-ui.button :href="route('admin.movements.index')" navigate>
                    Manage Movements
                </x-ui.button>
            </x-slot:actions>
        </x-ui.page-header>

        <section class="grid gap-5 md:grid-cols-2 xl:grid-cols-5">
            <x-ui.card>
                <p class="text-sm text-stone-400">
                    Core Movement Slots
                </p>

                <p class="mt-3 text-4xl font-semibold text-stone-100">
                    {{ $totalMovementSlots }}
                </p>

                <p class="mt-2 text-sm text-stone-500">
                    4 gates × 2 aspects × 6 realms
                </p>
            </x-ui.card>

            <x-ui.card>
                <p class="text-sm text-stone-400">
                    Public-Ready Movements
                </p>

                <p class="mt-3 text-4xl font-semibold text-amber-300">
                    {{ $publicReadyMovements }}
                </p>

                <p class="mt-2 text-sm text-stone-500">
                    Published with completed media
                </p>
            </x-ui.card>

            <x-ui.card>
                <p class="text-sm text-stone-400">
                    Processed Media
                </p>

                <p class="mt-3 text-4xl font-semibold text-teal-300">
                    {{ $processedMediaAssets }}
                </p>

                <p class="mt-2 text-sm text-stone-500">
                    MP4, GIF, and thumbnail generated
                </p>
            </x-ui.card>

            <x-ui.card>
                <p class="text-sm text-stone-400">
                    Phrases
                </p>

                <p class="mt-3 text-4xl font-semibold text-stone-100">
                    {{ $phraseCount }}
                </p>

                <p class="mt-2 text-sm text-stone-500">
                    {{ $publishedPhrases }} published / {{ $featuredPhrases }} featured
                </p>
            </x-ui.card>
            <x-ui.card>
                <p class="text-sm text-stone-400">
                    Generated Phrase Media
                </p>

                <p class="mt-2 text-3xl font-semibold text-teal-300">
                    {{ $generatedPhraseMediaCount }}
                </p>

                <p class="mt-1 text-sm text-stone-500">
                    Complete MP4/GIF phrase previews.
                </p>

                @if ($processingPhraseMediaCount || $outdatedPhraseMediaCount || $failedPhraseMediaCount)
                    <div class="mt-4 flex flex-wrap gap-2">
                        @if ($processingPhraseMediaCount)
                            <x-ui.badge variant="amber">
                                {{ $processingPhraseMediaCount }} Processing
                            </x-ui.badge>
                        @endif

                        @if ($outdatedPhraseMediaCount)
                            <x-ui.badge variant="amber">
                                {{ $outdatedPhraseMediaCount }} Outdated
                            </x-ui.badge>
                        @endif

                        @if ($failedPhraseMediaCount)
                            <x-ui.badge variant="danger">
                                {{ $failedPhraseMediaCount }} Failed
                            </x-ui.badge>
                        @endif
                    </div>
                @endif
            </x-ui.card>
        </section>

        <section class="grid gap-8 xl:grid-cols-[1.2fr_0.8fr]">
            <x-ui.panel>
                <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                    <x-ui.section-heading
                        title="Atlas Progress"
                        description="How much of the {{ $totalMovementSlots }}-slot movement atlas is ready for the public library."
                    />

                    <x-ui.badge variant="amber">
                        {{ $atlasProgressPercent }}% Ready
                    </x-ui.badge>
                </div>

                <div class="mt-6 space-y-6">
                    <div>
                        <div class="mb-2 flex items-center justify-between text-sm">
                            <span class="text-stone-400">Published + processed movement tiles</span>
                            <span class="font-medium text-stone-200">
                                {{ $publicReadyMovements }} / {{ $totalMovementSlots }}
                            </span>
                        </div>

                        <div class="h-3 overflow-hidden rounded-full bg-stone-800">
                            <div
                                class="h-full rounded-full bg-amber-500"
                                style="width: {{ $atlasProgressPercent }}%"
                            ></div>
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-3">
                        <div class="rounded-2xl border border-stone-800 bg-stone-950/50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">
                                Published
                            </p>

                            <p class="mt-2 text-2xl font-semibold text-stone-100">
                                {{ $publishedMovements }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-stone-800 bg-stone-950/50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">
                                Uploaded
                            </p>

                            <p class="mt-2 text-2xl font-semibold text-stone-100">
                                {{ $uploadedMediaAssets }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-stone-800 bg-stone-950/50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">
                                Failed
                            </p>

                            <p class="mt-2 text-2xl font-semibold {{ $failedMediaAssets ? 'text-red-300' : 'text-stone-100' }}">
                                {{ $failedMediaAssets }}
                            </p>
                        </div>
                    </div>
                </div>
            </x-ui.panel>

            <x-ui.panel>
                <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                    <x-ui.section-heading
                        title="Media Pipeline"
                        description="Raw upload to processed public-ready assets."
                    />

                    <x-ui.badge variant="teal">
                        {{ $mediaProgressPercent }}% Processed
                    </x-ui.badge>
                </div>

                <div class="mt-6">
                    <div class="mb-2 flex items-center justify-between text-sm">
                        <span class="text-stone-400">Processed assets</span>
                        <span class="font-medium text-stone-200">
                            {{ $processedMediaAssets }} / {{ $totalMovementSlots }}
                        </span>
                    </div>

                    <div class="h-3 overflow-hidden rounded-full bg-stone-800">
                        <div
                            class="h-full rounded-full bg-teal-500"
                            style="width: {{ $mediaProgressPercent }}%"
                        ></div>
                    </div>
                </div>

                <dl class="mt-6 space-y-3 text-sm">
                    <div class="flex items-center justify-between gap-4">
                        <dt class="text-stone-500">Movement records with media rows</dt>
                        <dd class="font-medium text-stone-200">{{ $mediaAssets }}</dd>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <dt class="text-stone-500">Raw video uploaded</dt>
                        <dd class="font-medium text-stone-200">{{ $uploadedMediaAssets }}</dd>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <dt class="text-stone-500">Generated public assets</dt>
                        <dd class="font-medium text-stone-200">{{ $processedMediaAssets }}</dd>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <dt class="text-stone-500">Processing failures</dt>
                        <dd class="font-medium {{ $failedMediaAssets ? 'text-red-300' : 'text-stone-200' }}">
                            {{ $failedMediaAssets }}
                        </dd>
                    </div>
                </dl>
            </x-ui.panel>
        </section>

        <section class="grid gap-8 xl:grid-cols-3">
            <x-ui.card hover>
                <div class="flex h-full flex-col justify-between gap-6">
                    <div>
                        <p class="fb-heading-kicker">
                            Movement Atlas
                        </p>

                        <h2 class="mt-3 text-2xl font-semibold tracking-tight text-stone-100">
                            Manage core movement slots.
                        </h2>

                        <p class="mt-3 text-sm leading-6 text-stone-400">
                            Edit titles, positions, difficulty, publishing status, descriptions, and teaching notes.
                        </p>
                    </div>

                    <x-ui.button :href="route('admin.movements.index')" navigate>
                        Open Movements
                    </x-ui.button>
                </div>
            </x-ui.card>

            <x-ui.card hover>
                <div class="flex h-full flex-col justify-between gap-6">
                    <div>
                        <p class="fb-heading-kicker">
                            Media Processing
                        </p>

                        <h2 class="mt-3 text-2xl font-semibold tracking-tight text-stone-100">
                            Upload, trim, and process clips.
                        </h2>

                        <p class="mt-3 text-sm leading-6 text-stone-400">
                            Each movement can generate a clean MP4, GIF preview, and thumbnail for the public library.
                        </p>
                    </div>

                    <x-ui.button :href="route('admin.movements.index')" variant="secondary" navigate>
                        Choose Movement
                    </x-ui.button>
                </div>
            </x-ui.card>

            <x-ui.card hover>
                <div class="flex h-full flex-col justify-between gap-6">
                    <div>
                        <p class="fb-heading-kicker">
                            Phrase Builder
                        </p>

                        <h2 class="mt-3 text-2xl font-semibold tracking-tight text-stone-100">
                            Build phrase paths.
                        </h2>

                        <p class="mt-3 text-sm leading-6 text-stone-400">
                            Arrange movement tiles into repeatable pathways and publish them as public phrase examples.
                        </p>
                    </div>

                    <x-ui.button :href="route('admin.sequences.index')" variant="secondary" navigate>
                        Open Phrase Builder
                    </x-ui.button>
                </div>
            </x-ui.card>
        </section>

        <section class="grid gap-8 xl:grid-cols-2">
            <x-ui.panel>
                <div class="flex items-start justify-between gap-4">
                    <x-ui.section-heading
                        title="Recently Updated Movements"
                        description="Latest movement records changed in the atlas."
                    />

                    <x-ui.button :href="route('admin.movements.index')" variant="ghost" navigate>
                        View All
                    </x-ui.button>
                </div>

                <div class="mt-6 space-y-4">
                    @forelse ($recentMovements as $movement)
                        <div class="rounded-2xl border border-stone-800 bg-stone-950/50 p-4">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <h3 class="font-semibold text-stone-100">
                                        {{ $movement->title }}
                                    </h3>

                                    <p class="mt-1 text-sm text-stone-500">
                                        Updated {{ $movement->updated_at->diffForHumans() }}
                                    </p>
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    <x-ui.badge variant="{{ $movement->status->value === 'published' ? 'green' : 'slate' }}">
                                        {{ $movement->status->label() }}
                                    </x-ui.badge>

                                    @if ($movement->mediaAsset)
                                        <x-ui.badge variant="{{ $movement->mediaAsset->processing_status->value === 'complete' ? 'teal' : 'amber' }}">
                                            {{ str($movement->mediaAsset->processing_status->value)->headline() }}
                                        </x-ui.badge>
                                    @else
                                        <x-ui.badge>
                                            No Media
                                        </x-ui.badge>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-4">
                                <x-floorbenders.taxonomy-row :movement="$movement" compact />
                            </div>

                            <div class="mt-4">
                                <x-ui.button
                                    :href="route('admin.movements.edit', $movement)"
                                    variant="secondary"
                                    navigate
                                >
                                    Edit
                                </x-ui.button>
                            </div>
                        </div>
                    @empty
                        <x-ui.empty-state
                            title="No movement updates yet."
                            description="Seeded movement slots will appear here once records exist."
                        />
                    @endforelse
                </div>
            </x-ui.panel>

            <x-ui.panel>
                <div class="flex items-start justify-between gap-4">
                    <x-ui.section-heading
                        title="Recent Phrases"
                        description="Latest phrase paths created or edited."
                    />

                    <x-ui.button :href="route('admin.sequences.index')" variant="ghost" navigate>
                        View All
                    </x-ui.button>
                </div>

                <div class="mt-6 space-y-4">
                    @forelse ($recentPhrases as $sequence)
                        <div class="rounded-2xl border border-stone-800 bg-stone-950/50 p-4">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <h3 class="font-semibold text-stone-100">
                                        {{ $sequence->title }}
                                    </h3>

                                    <p class="mt-1 text-sm text-stone-500">
                                        Updated {{ $sequence->updated_at->diffForHumans() }}
                                    </p>
                                </div>

                                <x-ui.badge variant="{{ $sequence->status->value === 'published' ? 'green' : 'slate' }}">
                                    {{ $sequence->status->label() }}
                                </x-ui.badge>
                            </div>

                            <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                                <div class="rounded-xl border border-stone-800 bg-stone-900/70 p-3">
                                    <p class="text-xs text-stone-500">
                                        Steps
                                    </p>

                                    <p class="mt-1 font-medium text-stone-200">
                                        {{ $sequence->sequence_movements_count }}
                                    </p>
                                </div>

                                <div class="rounded-xl border border-stone-800 bg-stone-900/70 p-3">
                                    <p class="text-xs text-stone-500">
                                        Featured
                                    </p>

                                    <p class="mt-1 font-medium text-stone-200">
                                        {{ $sequence->featured ? 'Yes' : 'No' }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-4 flex flex-wrap gap-2">
                                <x-ui.button
                                    :href="route('admin.sequences.edit', $sequence)"
                                    variant="secondary"
                                    navigate
                                >
                                    Edit Phrase
                                </x-ui.button>

                                @if ($sequence->status->value === 'published')
                                    <x-ui.button
                                        :href="route('sequences.show', $sequence)"
                                        variant="ghost"
                                        navigate
                                    >
                                        View
                                    </x-ui.button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <x-ui.empty-state
                            title="No phrases yet."
                            description="Create your first phrase path from the Phrase Builder."
                        >
                            <x-slot:actions>
                                <x-ui.button :href="route('admin.sequences.create')" navigate>
                                    Create Phrase
                                </x-ui.button>
                            </x-slot:actions>
                        </x-ui.empty-state>
                    @endforelse
                </div>
            </x-ui.panel>
        </section>
    </div>
</x-layouts.admin>
