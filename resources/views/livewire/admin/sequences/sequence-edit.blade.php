<div class="space-y-8">
    <div>
        <a
            href="{{ route('admin.sequences.index') }}"
            wire:navigate
            class="inline-flex items-center gap-2 text-sm font-medium text-stone-400 transition hover:text-amber-300"
        >
            <span aria-hidden="true">←</span>
            Back to Phrase Builder
        </a>
    </div>

    <x-ui.page-header
        kicker="Phrase Builder"
        :title="$sequence->title"
        description="Build a phrase path by adding, repeating, removing, and reordering movement tiles."
    >
        <x-slot:actions>
            @if ($sequence->status->value === 'published')
                <x-ui.button
                    :href="route('sequences.show', $sequence)"
                    variant="secondary"
                    navigate
                >
                    View Public Phrase
                </x-ui.button>
            @endif
        </x-slot:actions>
    </x-ui.page-header>

    @if (session('status'))
        <x-ui.alert>
            {{ session('status') }}
        </x-ui.alert>
    @endif

    <section class="grid gap-8 xl:grid-cols-[0.9fr_1.1fr]">
        <div class="space-y-6">
            <form wire:submit="saveDetails" class="space-y-6">
                <x-ui.panel>
                    <x-ui.section-heading
                        title="Phrase Details"
                        description="Update the title, description, publishing status, and featured flag."
                    />

                    <div class="mt-6 space-y-5">
                        <x-ui.input
                            name="title"
                            label="Title"
                            wire:model="title"
                            :error="$errors->first('title')"
                        />

                        <x-ui.textarea
                            name="description"
                            label="Description"
                            rows="5"
                            wire:model="description"
                            :error="$errors->first('description')"
                        />

                        <div class="grid gap-5 md:grid-cols-2">
                            <x-ui.select
                                name="status"
                                label="Status"
                                wire:model="status"
                                :error="$errors->first('status')"
                            >
                                @foreach ($statuses as $statusOption)
                                    <option value="{{ $statusOption->value }}">
                                        {{ $statusOption->label() }}
                                    </option>
                                @endforeach
                            </x-ui.select>

                            <label class="flex items-center gap-3 rounded-xl border border-stone-800 bg-stone-950/60 p-4">
                                <input
                                    type="checkbox"
                                    wire:model="featured"
                                    class="rounded border-stone-700 bg-stone-950 text-amber-500 focus:ring-amber-400"
                                >

                                <span>
                                    <span class="block text-sm font-medium text-stone-200">
                                        Featured phrase
                                    </span>

                                    <span class="block text-sm text-stone-500">
                                        Highlight this phrase as an example.
                                    </span>
                                </span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <x-ui.button type="submit">
                            Save Details
                        </x-ui.button>
                    </div>
                </x-ui.panel>
            </form>

            <x-floorbenders.pattern-summary
                :layer-pattern="$layerPattern"
                :orientation-pattern="$orientationPattern"
                :aspect-pattern="$aspectPattern"
                :realm-pattern="$realmPattern"
                :note-pattern="$notePattern"
            />

            <x-ui.panel>
                <x-ui.section-heading
                    title="Phrase Preview"
                    description="A visual playlist of the current phrase path."
                />

                @if ($sequenceMovements->count())
                    <div class="mt-6 space-y-5">
                        @foreach ($sequenceMovements as $sequenceMovement)
                            <div class="overflow-hidden rounded-3xl border border-stone-800 bg-stone-950/70">
                                <div class="grid gap-0 md:grid-cols-[220px_1fr]">
                                    <div class="aspect-video bg-black md:aspect-auto">
                                        @if ($sequenceMovement->movement->mediaAsset?->gif_path)
                                            <img
                                                src="{{ $sequenceMovement->movement->mediaAsset->gifUrl() }}"
                                                alt="Preview of {{ $sequenceMovement->movement->title }}"
                                                class="h-full w-full object-cover"
                                                loading="lazy"
                                            >
                                        @elseif ($sequenceMovement->movement->mediaAsset?->thumbnail_path)
                                            <img
                                                src="{{ $sequenceMovement->movement->mediaAsset->thumbnailUrl() }}"
                                                alt="Thumbnail of {{ $sequenceMovement->movement->title }}"
                                                class="h-full w-full object-cover"
                                                loading="lazy"
                                            >
                                        @else
                                            <div class="flex h-full min-h-32 items-center justify-center text-sm text-stone-600">
                                                No preview
                                            </div>
                                        @endif
                                    </div>

                                    <div class="space-y-4 p-5">
                                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-amber-400">
                                                        Step {{ $loop->iteration }}
                                                    </p>

                                                    <x-floorbenders.note-badge :movement="$sequenceMovement->movement" />
                                                </div>

                                                <h3 class="mt-1 text-lg font-semibold text-stone-100">
                                                    {{ $sequenceMovement->movement->title }}
                                                </h3>

                                                <p class="mt-1 text-sm text-stone-500">
                                                    {{ $sequenceMovement->movement->start_position ?: 'Start not set' }}
                                                    →
                                                    {{ $sequenceMovement->movement->end_position ?: 'End not set' }}
                                                </p>
                                            </div>

                                            <div class="flex flex-wrap gap-2">
                                                <x-ui.button
                                                    variant="secondary"
                                                    wire:click="moveUp({{ $sequenceMovement->id }})"
                                                >
                                                    ↑
                                                </x-ui.button>

                                                <x-ui.button
                                                    variant="secondary"
                                                    wire:click="moveDown({{ $sequenceMovement->id }})"
                                                >
                                                    ↓
                                                </x-ui.button>

                                                <x-ui.button
                                                    variant="danger"
                                                    wire:click="removeMovement({{ $sequenceMovement->id }})"
                                                >
                                                    Remove
                                                </x-ui.button>
                                            </div>
                                        </div>

                                        <x-floorbenders.taxonomy-row :movement="$sequenceMovement->movement" compact />
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <x-ui.empty-state
                        title="No movement steps yet."
                        description="Use the movement tile browser to add the first movement to this phrase path."
                        class="mt-6"
                    />
                @endif
            </x-ui.panel>

            <x-ui.panel>
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <x-ui.section-heading
                        title="Generated Phrase Media"
                        description="Create a single phrase MP4, GIF preview, and thumbnail from the ordered movement steps."
                    />

                    <x-ui.badge :variant="$sequence->phrase_processing_status->badgeVariant()">
                        {{ $sequence->phrase_processing_status->label() }}
                    </x-ui.badge>
                </div>

                <div class="mt-6 grid gap-4 md:grid-cols-3">
                    <div class="rounded-2xl border border-stone-800 bg-stone-950/50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">
                            Steps
                        </p>

                        <p class="mt-2 text-2xl font-semibold text-stone-100">
                            {{ $sequenceMovements->count() }}
                        </p>

                        <p class="mt-1 text-sm text-stone-500">
                            Recommended max: {{ config('floorbenders.media.phrase_max_recommended_steps', 12) }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-stone-800 bg-stone-950/50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">
                            Estimated Duration
                        </p>

                        <p class="mt-2 text-2xl font-semibold text-stone-100">
                            {{ number_format($estimatedPhraseSeconds, 1) }}s
                        </p>

                        <p class="mt-1 text-sm text-stone-500">
                            Recommended max: {{ config('floorbenders.media.phrase_max_recommended_seconds', 60) }}s
                        </p>
                    </div>

                    <div class="rounded-2xl border border-stone-800 bg-stone-950/50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">
                            Ready
                        </p>

                        <p class="mt-2 text-2xl font-semibold {{ $canGeneratePhraseMedia ? 'text-teal-300' : 'text-amber-300' }}">
                            {{ $canGeneratePhraseMedia ? 'Yes' : 'No' }}
                        </p>

                        <p class="mt-1 text-sm text-stone-500">
                            All steps need completed MP4 media.
                        </p>
                    </div>
                </div>

                @if ($phraseStepWarning || $phraseDurationWarning)
                    <div class="mt-5 space-y-3">
                        @if ($phraseStepWarning)
                            <x-ui.alert variant="warning">
                                This phrase has more than {{ config('floorbenders.media.phrase_max_recommended_steps', 12) }} steps. It can still process, but the generated GIF may become large.
                            </x-ui.alert>
                        @endif

                        @if ($phraseDurationWarning)
                            <x-ui.alert variant="warning">
                                This phrase is estimated over {{ config('floorbenders.media.phrase_max_recommended_seconds', 60) }} seconds. The MP4 should be fine, but the GIF may become heavy.
                            </x-ui.alert>
                        @endif

                            @if ($sequence->phrase_processing_status === \App\Enums\SequenceMediaProcessingStatus::Complete && ! $phraseMediaIsCurrent)
                                <div class="mt-5">
                                    <x-ui.alert variant="warning">
                                        This generated phrase media no longer matches the current movement path. Regenerate it to update the MP4, GIF, and thumbnail.
                                    </x-ui.alert>
                                </div>
                            @endif

                            @if ($sequence->phrase_processing_status === \App\Enums\SequenceMediaProcessingStatus::Stale)
                                <div class="mt-5">
                                    <x-ui.alert variant="warning">
                                        This phrase has changed since the media was generated. Regenerate phrase media before publishing or sharing this phrase preview.
                                    </x-ui.alert>
                                </div>
                            @endif
                    </div>
                @endif

                @if ($sequence->phrase_processing_error)
                    <div class="mt-5">
                        <x-ui.alert variant="danger">
                            {{ $sequence->phrase_processing_error }}
                        </x-ui.alert>
                    </div>
                @endif

                <div class="mt-6 flex flex-wrap gap-3">
                    <x-ui.button
                        wire:click="generatePhraseMedia"
                        wire:loading.attr="disabled"
                        wire:target="generatePhraseMedia"
                        :variant="$sequence->hasGeneratedPhraseMedia() ? 'secondary' : 'primary'"
                        @disabled(! $canGeneratePhraseMedia || $sequence->phrase_processing_status === \App\Enums\SequenceMediaProcessingStatus::Processing)
                    >
            <span wire:loading.remove wire:target="generatePhraseMedia">
                {{ $sequence->hasGeneratedPhraseMedia() ? 'Regenerate Phrase Media' : 'Generate Phrase Media' }}
            </span>

                        <span wire:loading wire:target="generatePhraseMedia">
                Starting...
            </span>
                    </x-ui.button>

                    @if (! $canGeneratePhraseMedia)
                        <p class="self-center text-sm text-stone-500">
                            Add movement steps with completed media before generating phrase media.
                        </p>
                    @endif

                    @if ($sequence->phrase_processing_status === \App\Enums\SequenceMediaProcessingStatus::Processing)
                        <p class="self-center text-sm text-amber-300">
                            Processing in queue. Refresh after the worker finishes.
                        </p>
                    @endif

                    @if ($sequence->phrase_video_path || $sequence->phrase_gif_path || $sequence->phrase_thumbnail_path)
                        <x-ui.button
                            type="button"
                            variant="secondary"
                            wire:click="clearPhraseMedia"
                            wire:loading.attr="disabled"
                            wire:target="clearPhraseMedia"
                            @disabled($sequence->phrase_processing_status === \App\Enums\SequenceMediaProcessingStatus::Processing)
                        >
        <span wire:loading.remove wire:target="clearPhraseMedia">
            Clear Generated Media
        </span>

                            <span wire:loading wire:target="clearPhraseMedia">
            Clearing...
        </span>
                        </x-ui.button>
                    @endif
                </div>

                @if ($sequence->phrase_video_path || $sequence->phrase_gif_path || $sequence->phrase_thumbnail_path)
                    <div class="mt-8 border-t border-stone-800 pt-6">
                        <x-ui.section-heading
                            title="Generated Assets"
                            :description="$phraseMediaIsCurrent
        ? 'These files match the current phrase path.'
        : 'These files may be outdated and should be regenerated.'"
                        />

                        @if (! $phraseMediaIsCurrent)
                            <div class="mb-5">
                                <x-ui.alert variant="warning">
                                    Previewing previously generated files. They may not match the current phrase order.
                                </x-ui.alert>
                            </div>
                        @endif

                        <div class="mt-6 grid gap-6 lg:grid-cols-3">
                            @if ($sequence->phrase_video_path)
                                <div>
                                    <p class="mb-2 text-sm font-medium text-stone-300">
                                        Phrase MP4
                                    </p>

                                    <div class="overflow-hidden rounded-2xl border border-stone-800 bg-black">
                                        <video
                                            controls
                                            playsinline
                                            preload="metadata"
                                            poster="{{ $sequence->phraseThumbnailUrl() }}"
                                            class="aspect-video w-full bg-black object-contain"
                                        >
                                            <source src="{{ $sequence->phraseVideoUrl() }}" type="video/mp4">
                                        </video>
                                    </div>
                                </div>
                            @endif

                            @if ($sequence->phrase_gif_path)
                                <div>
                                    <p class="mb-2 text-sm font-medium text-stone-300">
                                        Phrase GIF
                                    </p>

                                    <div class="overflow-hidden rounded-2xl border border-stone-800 bg-stone-950">
                                        <img
                                            src="{{ $sequence->phraseGifUrl() }}"
                                            alt="Generated GIF preview for {{ $sequence->title }}"
                                            class="aspect-video w-full object-cover"
                                            loading="lazy"
                                        >
                                    </div>
                                </div>
                            @endif

                            @if ($sequence->phrase_thumbnail_path)
                                <div>
                                    <p class="mb-2 text-sm font-medium text-stone-300">
                                        Phrase Thumbnail
                                    </p>

                                    <div class="overflow-hidden rounded-2xl border border-stone-800 bg-stone-950">
                                        <img
                                            src="{{ $sequence->phraseThumbnailUrl() }}"
                                            alt="Generated thumbnail for {{ $sequence->title }}"
                                            class="aspect-video w-full object-cover"
                                            loading="lazy"
                                        >
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </x-ui.panel>
        </div>

        <div class="space-y-6">
            <x-ui.panel>
                <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    <x-ui.section-heading
                        title="Add Movement Tiles"
                        description="Search published movements and add them to the phrase path. Movements can be repeated."
                    />

                    <x-ui.button variant="secondary" wire:click="clearMovementFilters">
                        Clear Filters
                    </x-ui.button>
                </div>

                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <x-ui.input
                        name="movementSearch"
                        label="Search"
                        type="search"
                        wire:model.live.debounce.300ms="movementSearch"
                        placeholder="Search movement title..."
                    />

                    <x-ui.select name="movementGate" label="Gate" wire:model.live="movementGate">
                        <option value="">All Gates</option>

                        @foreach ($gates as $gateOption)
                            <option value="{{ $gateOption->value }}">
                                {{ $gateOption->label() }}
                            </option>
                        @endforeach
                    </x-ui.select>

                    <x-ui.select name="movementAspect" label="Aspect" wire:model.live="movementAspect">
                        <option value="">All Aspects</option>

                        @foreach ($aspects as $aspectOption)
                            <option value="{{ $aspectOption->value }}">
                                {{ $aspectOption->label() }}
                            </option>
                        @endforeach
                    </x-ui.select>

                    <x-ui.select name="movementRealm" label="Realm" wire:model.live="movementRealm">
                        <option value="">All Realms</option>

                        @foreach ($realms as $realmOption)
                            <option value="{{ $realmOption->value }}">
                                {{ $realmOption->label() }}
                            </option>
                        @endforeach
                    </x-ui.select>

                    <x-ui.select name="movementOrientation" label="Orientation" wire:model.live="movementOrientation">
                        <option value="">All Orientations</option>

                        @foreach ($orientations as $orientationOption)
                            <option value="{{ $orientationOption->value }}">
                                {{ $orientationOption->label() }}
                            </option>
                        @endforeach
                    </x-ui.select>

                    <x-ui.select name="movementLayer" label="Layer" wire:model.live="movementLayer">
                        <option value="">All Layers</option>

                        @foreach ($layers as $layerOption)
                            <option value="{{ $layerOption->value }}">
                                {{ $layerOption->label() }}
                            </option>
                        @endforeach
                    </x-ui.select>

                    <x-ui.select name="movementDifficulty" label="Difficulty" wire:model.live="movementDifficulty">
                        <option value="">All Difficulties</option>

                        @foreach ($difficulties as $difficultyOption)
                            <option value="{{ $difficultyOption->value }}">
                                {{ $difficultyOption->label() }}
                            </option>
                        @endforeach
                    </x-ui.select>
                </div>
            </x-ui.panel>

            <section class="space-y-4">
                <div class="flex items-end justify-between gap-4">
                    <div>
                        <p class="fb-heading-kicker">
                            Movement Tiles
                        </p>

                        <h2 class="mt-2 text-2xl font-semibold tracking-tight text-stone-100">
                            Available Movements
                        </h2>
                    </div>

                    <p class="text-sm text-stone-500">
                        {{ $availableMovements->count() }} available
                    </p>
                </div>

                @if ($availableMovements->count())
                    <div class="grid gap-5 md:grid-cols-2">
                        @foreach ($availableMovements as $movement)
                            <x-floorbenders.movement-mini-card
                                :movement="$movement"
                                action-label="Add to Phrase"
                                action="addMovement({{ $movement->id }})"
                            />
                        @endforeach
                    </div>
                @else
                    <x-ui.empty-state
                        title="No movements match these filters."
                        description="Try clearing filters or publishing more processed movements."
                    >
                        <x-slot:actions>
                            <x-ui.button wire:click="clearMovementFilters">
                                Clear Movement Filters
                            </x-ui.button>
                        </x-slot:actions>
                    </x-ui.empty-state>
                @endif
            </section>
        </div>
    </section>
</div>
