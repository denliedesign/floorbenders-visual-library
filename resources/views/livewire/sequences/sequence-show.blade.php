<div class="space-y-8">
    <div>
        <a
            href="{{ route('sequences.index') }}"
            wire:navigate
            class="inline-flex items-center gap-2 text-sm font-medium text-stone-400 transition hover:text-amber-300"
        >
            <span aria-hidden="true">←</span>
            Back to Phrases
        </a>
    </div>

    <section class="relative overflow-hidden rounded-3xl border border-stone-800 bg-stone-950 shadow-2xl shadow-black/30">
        <div class="absolute inset-0 opacity-70">
            <div class="absolute -left-24 -top-24 h-80 w-80 rounded-full bg-amber-500/10 blur-3xl"></div>
            <div class="absolute -bottom-32 right-0 h-96 w-96 rounded-full bg-teal-500/10 blur-3xl"></div>
        </div>

        <div class="relative grid gap-8 px-6 py-8 lg:grid-cols-[1.2fr_0.8fr] lg:px-10">
            <div>
                <p class="fb-heading-kicker">
                    Public Phrase
                </p>

                <h1 class="mt-4 text-4xl font-semibold tracking-tight text-stone-100 sm:text-5xl">
                    {{ $sequence->title }}
                </h1>

                <p class="mt-5 max-w-2xl text-base leading-7 text-stone-400">
                    {{ $sequence->description ?: 'A Floorbenders phrase path built from published movement tiles.' }}
                </p>
            </div>

            <div class="fb-panel-soft p-5">
                <p class="text-sm font-medium text-stone-300">
                    Phrase Summary
                </p>

                <dl class="mt-4 space-y-3 text-sm">
                    <div class="flex items-center justify-between gap-4">
                        <dt class="text-stone-500">Steps</dt>
                        <dd class="font-medium text-stone-200">
                            {{ $sequenceMovements->count() }}
                        </dd>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <dt class="text-stone-500">Status</dt>
                        <dd class="font-medium text-stone-200">
                            {{ $sequence->status->label() }}
                        </dd>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <dt class="text-stone-500">Featured</dt>
                        <dd class="font-medium text-stone-200">
                            {{ $sequence->featured ? 'Yes' : 'No' }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </section>

    <x-floorbenders.pattern-summary
        :layer-pattern="$layerPattern"
        :orientation-pattern="$orientationPattern"
        :aspect-pattern="$aspectPattern"
        :realm-pattern="$realmPattern"
        :note-pattern="$notePattern"
    />

    @if ($hasGeneratedPhraseMedia)
        <x-ui.panel class="mt-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <x-ui.section-heading
                    title="Generated Phrase Preview"
                    description="The complete phrase path rendered as one continuous media sequence."
                />

                <x-ui.badge variant="teal">
                    Complete Phrase
                </x-ui.badge>
            </div>

            <div class="mt-6 overflow-hidden rounded-3xl border border-stone-800 bg-black shadow-2xl shadow-black/40">
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

            <div class="mt-5 flex flex-wrap items-center justify-between gap-3 text-sm text-stone-500">
                <p>
                    Generated from {{ $sequenceMovements->count() }} ordered movement {{ \Illuminate\Support\Str::plural('step', $sequenceMovements->count()) }}.
                </p>

                @if ($sequence->phrase_processed_at)
                    <p>
                        Processed {{ $sequence->phrase_processed_at->diffForHumans() }}
                    </p>
                @endif
            </div>

            <details class="mt-6 rounded-2xl border border-stone-800 bg-stone-950/70 p-4">
                <summary class="cursor-pointer text-sm font-semibold text-amber-300">
                    View Phrase at a Glance
                </summary>

                <div class="mt-4">
                    <p class="max-w-3xl text-sm leading-6 text-stone-400">
                        A quick scannable view of the phrase, shown as individual movement GIFs in sequence order.
                    </p>

                    <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                        @foreach ($sequenceMovements as $sequenceMovement)
                            @php
                                $movement = $sequenceMovement->movement;
                                $mediaAsset = $movement?->mediaAsset;
                            @endphp

                            @if ($movement && $mediaAsset?->gif_path)
                                <a
                                    href="{{ route('library.show', $movement) }}"
                                    wire:navigate
                                    class="group overflow-hidden rounded-2xl border border-stone-800 bg-stone-950 transition hover:border-amber-500/40"
                                >
                                    <div class="relative aspect-video overflow-hidden bg-black">
                                        <img
                                            src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($mediaAsset->gif_path) }}"
                                            alt="GIF preview for {{ $movement->title }}"
                                            class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                            loading="lazy"
                                        >

                                        <div class="absolute left-3 top-3">
                                <span class="inline-flex rounded-full border border-black/40 bg-black/70 px-2.5 py-1 text-xs font-semibold text-stone-100">
                                    {{ $loop->iteration }}
                                </span>
                                        </div>

                                        <div class="absolute right-3 top-3">
                                            <x-floorbenders.note-badge :movement="$movement" />
                                        </div>
                                    </div>

                                    <div class="p-4">
                                        <h3 class="line-clamp-1 text-sm font-semibold text-stone-100 group-hover:text-amber-300">
                                            {{ $movement->title }}
                                        </h3>
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </details>
        </x-ui.panel>
    @else
        <x-ui.panel class="mt-8">
            <x-ui.section-heading
                title="Phrase Preview"
                description="A generated full-phrase video has not been published yet. Explore the phrase through the individual movement steps below."
            />

            <div class="mt-5 rounded-2xl border border-dashed border-stone-700 bg-stone-950/60 p-6 text-sm text-stone-400">
                This phrase is currently shown as an ordered movement path. A full generated MP4/GIF preview can be added from the admin Phrase Builder.
            </div>
        </x-ui.panel>
    @endif

    <section class="space-y-5">
        <div>
            <p class="fb-heading-kicker">
                Phrase Path
            </p>

            <h2 class="mt-2 text-2xl font-semibold tracking-tight text-stone-100">
                Movement Steps
            </h2>

            <p class="mt-2 max-w-2xl text-sm text-stone-400">
                Each step remains a separate video tile so the phrase structure is easy to read and study. Feel free to slow down the video speed, pause, and replay as needed.
            </p>
        </div>

        @if ($sequenceMovements->count())
            <div class="space-y-6">
                @foreach ($sequenceMovements as $sequenceMovement)
                    <article class="overflow-hidden rounded-3xl border border-stone-800 bg-stone-900/70 shadow-xl shadow-black/20">
                        <div class="grid gap-0 lg:grid-cols-[1.2fr_0.8fr]">
                            <div class="bg-black">
                                <video
                                    controls
                                    playsinline
                                    preload="metadata"
                                    poster="{{ $sequenceMovement->movement->mediaAsset->thumbnailUrl() }}"
                                    class="aspect-video w-full bg-black object-contain"
                                >
                                    <source
                                        src="{{ $sequenceMovement->movement->mediaAsset->cleanVideoUrl() }}"
                                        type="video/mp4"
                                    >

                                    Your browser does not support the video tag.
                                </video>
                            </div>

                            <div class="space-y-5 p-6">
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-amber-400">
                                            Step {{ $loop->iteration }}
                                        </p>

                                        <x-floorbenders.note-badge :movement="$sequenceMovement->movement" />
                                    </div>

                                    <h3 class="mt-2 text-2xl font-semibold tracking-tight text-stone-100">
                                        {{ $sequenceMovement->movement->title }}
                                    </h3>

                                    <p class="mt-2 text-sm text-stone-500">
                                        {{ $sequenceMovement->movement->start_facing->label() }}
                                        →
                                        {{ $sequenceMovement->movement->end_facing->label() }}
                                    </p>
                                </div>

                                <x-floorbenders.taxonomy-row :movement="$sequenceMovement->movement" compact />

                                <div class="grid grid-cols-2 gap-3 text-sm">
                                    <div class="rounded-2xl border border-stone-800 bg-stone-950/50 p-3">
                                        <p class="text-xs text-stone-500">
                                            Orientation
                                        </p>

                                        <p class="mt-1 font-medium text-stone-200">
                                            {{ $sequenceMovement->movement->realm->orientation()->label() }}
                                        </p>
                                    </div>

                                    <div class="rounded-2xl border border-stone-800 bg-stone-950/50 p-3">
                                        <p class="text-xs text-stone-500">
                                            Layer
                                        </p>

                                        <p class="mt-1 font-medium text-stone-200">
                                            {{ $sequenceMovement->movement->realm->layer()->label() }}
                                        </p>
                                    </div>
                                </div>

                                @if ($sequenceMovement->movement->description)
                                    <p class="text-sm leading-6 text-stone-400">
                                        {{ str($sequenceMovement->movement->description)->limit(160) }}
                                    </p>
                                @endif

                                <x-ui.button
                                    :href="route('library.show', $sequenceMovement->movement)"
                                    variant="secondary"
                                    navigate
                                >
                                    Open Movement
                                </x-ui.button>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <x-ui.empty-state
                title="This phrase has no movement steps."
                description="Movement steps can be added from the admin Phrase Builder."
            />
        @endif
    </section>

    <section class="rounded-3xl border border-amber-500/20 bg-amber-500/10 p-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-amber-300">
                    Continue Exploring
                </p>

                <h2 class="mt-2 text-2xl font-semibold tracking-tight text-stone-100">
                    Explore the Movement Atlas
                </h2>

                <p class="mt-2 text-sm text-amber-100/80">
                    Compare the individual movement tiles that make this phrase possible.
                </p>
            </div>

            <x-ui.button :href="route('library.index')" navigate>
                Browse Atlas
            </x-ui.button>
        </div>
    </section>
</div>
