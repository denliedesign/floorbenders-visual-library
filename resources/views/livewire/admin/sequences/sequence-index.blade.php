<div class="space-y-8">
    <section class="fb-panel p-6">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <p class="fb-heading-kicker">
                    Phrase Builder
                </p>

                <h1 class="mt-3 text-3xl font-semibold tracking-tight text-stone-100 md:text-4xl">
                    Admin Phrase Dashboard
                </h1>

                <p class="mt-4 max-w-3xl text-sm leading-6 text-stone-400 md:text-base">
                    Build ordered Floorbenders phrase paths, review pattern summaries, and generate full MP4/GIF previews from completed movement media.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.sequences.create') }}" class="fb-button-primary">
                    New Phrase
                </a>
            </div>
        </div>

        <div class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-6">
            <div class="rounded-2xl border border-stone-800 bg-stone-950/70 p-4">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">
                    Total
                </p>

                <p class="mt-2 text-3xl font-semibold text-stone-100">
                    {{ $totalPhraseCount }}
                </p>
            </div>

            <div class="rounded-2xl border border-stone-800 bg-stone-950/70 p-4">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">
                    Published
                </p>

                <p class="mt-2 text-3xl font-semibold text-green-300">
                    {{ $publishedPhraseCount }}
                </p>
            </div>

            <div class="rounded-2xl border border-stone-800 bg-stone-950/70 p-4">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">
                    Featured
                </p>

                <p class="mt-2 text-3xl font-semibold text-amber-300">
                    {{ $featuredPhraseCount }}
                </p>
            </div>

            <div class="rounded-2xl border border-stone-800 bg-stone-950/70 p-4">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">
                    Generated
                </p>

                <p class="mt-2 text-3xl font-semibold text-teal-300">
                    {{ $generatedPhraseMediaCount }}
                </p>
            </div>

            <div class="rounded-2xl border border-stone-800 bg-stone-950/70 p-4">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">
                    Processing
                </p>

                <p class="mt-2 text-3xl font-semibold text-blue-300">
                    {{ $processingPhraseMediaCount }}
                </p>
            </div>

            <div class="rounded-2xl border border-stone-800 bg-stone-950/70 p-4">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">
                    Needs Attention
                </p>

                <p class="mt-2 text-3xl font-semibold text-red-300">
                    {{ $outdatedPhraseMediaCount + $failedPhraseMediaCount }}
                </p>
            </div>
        </div>
    </section>

    <section class="fb-panel p-6">
        <div class="grid gap-4 lg:grid-cols-5">
            <div class="lg:col-span-2">
                <label for="sequence-search" class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">
                    Search
                </label>

                <input
                    id="sequence-search"
                    type="search"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search phrase title or description..."
                    class="fb-input w-full"
                >
            </div>

            <div>
                <label for="sequence-status" class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">
                    Status
                </label>

                <select id="sequence-status" wire:model.live="status" class="fb-input w-full">
                    <option value="">All statuses</option>

                    @foreach ($statuses as $statusOption)
                        <option value="{{ $statusOption->value }}">
                            {{ $statusOption->label() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="sequence-media-status" class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">
                    Media
                </label>

                <select id="sequence-media-status" wire:model.live="mediaStatus" class="fb-input w-full">
                    <option value="">All media</option>

                    @foreach ($mediaStatuses as $mediaStatusOption)
                        <option value="{{ $mediaStatusOption->value }}">
                            {{ $mediaStatusOption->label() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="sequence-featured" class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">
                    Featured
                </label>

                <select id="sequence-featured" wire:model.live="featured" class="fb-input w-full">
                    <option value="">All phrases</option>
                    <option value="1">Featured only</option>
                    <option value="0">Not featured</option>
                </select>
            </div>
        </div>

        @if ($search || $status || $mediaStatus || $featured !== '')
            <div class="mt-5">
                <button type="button" wire:click="clearFilters" class="fb-button-secondary">
                    Clear Filters
                </button>
            </div>
        @endif
    </section>

    <section>
        @if ($sequences->count())
            <div class="grid gap-6 xl:grid-cols-1">
                @foreach ($sequences as $sequence)
                    @php
                        $stepCount = $sequence->sequenceMovements->count();

                        $notePattern = $sequence->sequenceMovements
                            ->map(fn ($sequenceMovement) => $sequenceMovement->movement?->atlasNote())
                            ->filter()
                            ->implode(' → ');

                        $layerPattern = $sequence->sequenceMovements
                            ->map(fn ($sequenceMovement) => $sequenceMovement->movement?->realm?->layer()->label())
                            ->filter()
                            ->implode(' → ');

                        $orientationPattern = $sequence->sequenceMovements
                            ->map(fn ($sequenceMovement) => $sequenceMovement->movement?->realm?->orientation()->label())
                            ->filter()
                            ->implode(' → ');
                    @endphp

                    <article class="fb-card overflow-hidden">
                        <div class="grid gap-6 lg:grid-cols-[220px_1fr]">
                            <div class="overflow-hidden border border-stone-800 bg-stone-950">
                                @if ($sequence->hasGeneratedPhraseMedia())
                                    <img
                                        src="{{ $sequence->phraseThumbnailUrl() }}"
                                        alt="Generated phrase thumbnail for {{ $sequence->title }}"
                                        class="aspect-video h-full w-full object-cover"
                                        loading="lazy"
                                    >
                                @elseif ($sequence->phrase_thumbnail_path)
                                    <div class="relative">
                                        <img
                                            src="{{ $sequence->phraseThumbnailUrl() }}"
                                            alt="Outdated phrase thumbnail for {{ $sequence->title }}"
                                            class="aspect-video h-full w-full object-cover opacity-40 grayscale"
                                            loading="lazy"
                                        >

                                        <div class="absolute inset-0 flex items-center justify-center bg-black/50 px-4 text-center">
                                            <x-ui.badge variant="amber">
                                                Outdated Preview
                                            </x-ui.badge>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex aspect-video h-full w-full items-center justify-center bg-stone-950 px-6 text-center text-sm text-stone-600">
                                        No generated preview yet
                                    </div>
                                @endif
                            </div>

                            <div class="min-w-0 pt-6 pr-6 pb-6">
                                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <x-ui.badge :variant="$sequence->status->value === 'published' ? 'green' : 'slate'">
                                                {{ $sequence->status->label() }}
                                            </x-ui.badge>

                                            <x-ui.badge :variant="$sequence->phraseMediaDisplayBadgeVariant()">
                                                {{ $sequence->phraseMediaDisplayLabel() }}
                                            </x-ui.badge>

                                            @if ($sequence->featured)
                                                <x-ui.badge variant="amber">
                                                    Featured
                                                </x-ui.badge>
                                            @endif
                                        </div>

                                        <h2 class="mt-3 text-2xl font-semibold tracking-tight text-stone-100">
                                            {{ $sequence->title }}
                                        </h2>

                                        @if ($sequence->description)
                                            <p class="mt-2 line-clamp-2 text-sm leading-6 text-stone-400">
                                                {{ $sequence->description }}
                                            </p>
                                        @else
                                            <p class="mt-2 text-sm leading-6 text-stone-600">
                                                No description yet.
                                            </p>
                                        @endif
                                    </div>

                                    <p class="shrink-0 text-sm text-stone-500">
                                        {{ $stepCount }} {{ \Illuminate\Support\Str::plural('step', $stepCount) }}
                                    </p>
                                </div>

                                <div class="mt-5 grid gap-3 md:grid-cols-3">
                                    <div class="rounded-2xl border border-stone-800 bg-stone-950/60 p-3">
                                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-amber-400">
                                            Notes
                                        </p>

                                        <p class="mt-2 text-sm leading-6 text-stone-300">
                                            {{ $notePattern ?: 'No notes yet' }}
                                        </p>
                                    </div>

                                    <div class="rounded-2xl border border-stone-800 bg-stone-950/60 p-3">
                                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-teal-300">
                                            Layers
                                        </p>

                                        <p class="mt-2 text-sm leading-6 text-stone-300">
                                            {{ $layerPattern ?: 'No layers yet' }}
                                        </p>
                                    </div>

                                    <div class="rounded-2xl border border-stone-800 bg-stone-950/60 p-3">
                                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-300">
                                            Orientation
                                        </p>

                                        <p class="mt-2 text-sm leading-6 text-stone-300">
                                            {{ $orientationPattern ?: 'No orientation yet' }}
                                        </p>
                                    </div>
                                </div>

                                @if ($sequence->phrase_processed_at)
                                    <p class="mt-4 text-xs text-stone-500">
                                        Phrase media processed {{ $sequence->phrase_processed_at->diffForHumans() }}.
                                    </p>
                                @endif

                                @if ($sequence->phrase_processing_error)
                                    <div class="mt-4 rounded-2xl border border-red-500/30 bg-red-500/10 p-4 text-sm leading-6 text-red-200">
                                        {{ $sequence->phrase_processing_error }}
                                    </div>
                                @endif

                                <div class="mt-6 flex flex-wrap gap-3">
                                    <a href="{{ route('admin.sequences.edit', $sequence) }}" class="fb-button-primary">
                                        Edit Phrase
                                    </a>

                                    @if ($sequence->status === \App\Enums\SequenceStatus::Published)
                                        <a href="{{ route('sequences.show', $sequence) }}" target="_blank" class="fb-button-secondary">
                                            View Public Page
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $sequences->links() }}
            </div>
        @else
            <div class="fb-panel p-4 text-center">
                <p class="fb-heading-kicker">
                    No phrases found
                </p>

                <h2 class="mt-3 text-2xl font-semibold text-stone-100">
                    Start building phrase paths.
                </h2>

                <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-stone-400">
                    Create a phrase, add ordered movement steps, review the pattern summaries, and generate complete MP4/GIF previews.
                </p>

                <div class="mt-6">
                    <a href="{{ route('admin.sequences.create') }}" class="fb-button-primary">
                        New Phrase
                    </a>
                </div>
            </div>
        @endif
    </section>
</div>
