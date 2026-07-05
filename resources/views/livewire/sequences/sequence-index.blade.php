<div class="space-y-10">
    <section class="fb-panel p-5 overflow-hidden">
        <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr] lg:items-center">
            <div>
                <p class="fb-heading-kicker">
                    Phrase Builder
                </p>

                <h1 class="mt-3 text-4xl font-semibold tracking-tight text-stone-100 md:text-5xl">
                    Floorbenders phrase paths.
                </h1>

                <p class="mt-5 max-w-3xl text-base leading-7 text-stone-400">
                    Browse ordered movement phrases built from the Movement Atlas. Each phrase reveals its layer, orientation, aspect, realm, and atlas-note patterns.
                </p>
            </div>
        </div>
    </section>

    <section class="fb-panel">
        <div class="grid p-5 gap-4 md:grid-cols-[1fr_220px_auto] md:items-end">
            <div>
                <label for="phrase-search" class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">
                    Search Phrases
                </label>

                <input
                    id="phrase-search"
                    type="search"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search phrase title or description..."
                    class="fb-input w-full"
                >
            </div>

            <div>
                <label for="phrase-preview-filter" class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">
                    View
                </label>

                <select id="phrase-preview-filter" wire:model.live="preview" class="fb-input w-full">
                    <option value="">All published</option>
                    <option value="featured">Featured only</option>
                </select>
            </div>

            <div>
                @if ($search || $preview)
                    <button type="button" wire:click="clearFilters" class="fb-button-secondary">
                        Clear
                    </button>
                @endif
            </div>
        </div>
    </section>

    <section>
        @if ($sequences->count())
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($sequences as $sequence)
                    @php
                        $stepCount = $sequence->sequenceMovements->count();

                        $notePattern = $sequence->sequenceMovements
                            ->map(fn ($sequenceMovement) => $sequenceMovement->movement?->atlasNote())
                            ->filter()
                            ->implode(' → ');

                        $aspectPattern = $sequence->sequenceMovements
                            ->map(fn ($sequenceMovement) => $sequenceMovement->movement?->aspect?->label())
                            ->filter()
                            ->implode(' → ');

                        $realmPattern = $sequence->sequenceMovements
                            ->map(fn ($sequenceMovement) => $sequenceMovement->movement?->realm?->label())
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

                    <a
                        href="{{ route('sequences.show', $sequence) }}"
                        wire:navigate
                        class="group fb-card block overflow-hidden transition hover:-translate-y-1 hover:border-amber-500/40"
                    >
                        <div class="overflow-hidden border border-stone-800 bg-stone-950">
                            @if ($sequence->hasGeneratedPhraseMedia())
                                <img
                                    src="{{ $sequence->phraseThumbnailUrl() }}"
                                    alt="Generated phrase thumbnail for {{ $sequence->title }}"
                                    class="aspect-video w-full object-cover transition duration-500 group-hover:scale-105"
                                    loading="lazy"
                                >
                            @else
                                <div class="flex aspect-video w-full items-center justify-center bg-[radial-gradient(circle_at_top,_rgba(245,158,11,0.16),_transparent_45%),linear-gradient(135deg,_rgba(12,10,9,1),_rgba(28,25,23,1))] px-6 text-center">
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-amber-300">
                                            Phrase Path
                                        </p>

                                        <p class="mt-3 text-sm leading-6 text-stone-500">
                                            Step-by-step movement sequence
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="p-5">
                            <div class="flex flex-wrap items-center gap-2">
                                @if ($sequence->hasGeneratedPhraseMedia())
                                    <x-ui.badge variant="teal">
                                        Generated Preview
                                    </x-ui.badge>
                                @else
                                    <x-ui.badge variant="slate">
                                        Step-by-Step Phrase
                                    </x-ui.badge>
                                @endif

                                @if ($sequence->featured)
                                    <x-ui.badge variant="amber">
                                        Featured
                                    </x-ui.badge>
                                @endif

                                <x-ui.badge variant="blue">
                                    {{ $stepCount }} {{ \Illuminate\Support\Str::plural('Step', $stepCount) }}
                                </x-ui.badge>
                            </div>

                            <h2 class="mt-4 text-2xl font-semibold tracking-tight text-stone-100 group-hover:text-amber-300">
                                {{ $sequence->title }}
                            </h2>

                            @if ($sequence->description)
                                <p class="mt-3 line-clamp-3 text-sm leading-6 text-stone-400">
                                    {{ $sequence->description }}
                                </p>
                            @else
                                <p class="mt-3 text-sm leading-6 text-stone-600">
                                    No description has been added yet.
                                </p>
                            @endif
                        </div>

                        <div class="px-5 pb-5 space-y-3">
                            <div class="rounded-2xl border border-stone-800 bg-stone-950/60 p-4">
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-amber-400">
                                    Note Pattern
                                </p>

                                <p class="mt-2 text-sm leading-6 text-stone-300">
                                    {{ $notePattern ?: 'No note pattern yet' }}
                                </p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $sequences->links() }}
            </div>
        @else
            <div class="fb-panel text-center">
                <p class="fb-heading-kicker">
                    No phrases found
                </p>

                <h2 class="mt-3 text-2xl font-semibold text-stone-100">
                    No published phrases match this search.
                </h2>

                <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-stone-400">
                    Try clearing the filters or explore the Movement Atlas while new phrases are being built.
                </p>

                <div class="mt-6 flex justify-center">
                    <a href="{{ route('library.index') }}" class="fb-button-secondary">
                        Explore Movement Atlas
                    </a>
                </div>
            </div>
        @endif
    </section>
</div>
