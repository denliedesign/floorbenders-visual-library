<div class="space-y-8">
    <div>
        <a
            href="{{ route('library.index') }}"
            wire:navigate
            class="inline-flex items-center gap-2 text-sm font-medium text-stone-400 transition hover:text-amber-300"
        >
            <span aria-hidden="true">←</span>
            Back to Movement Atlas
        </a>
    </div>

    <section class="relative overflow-hidden rounded-3xl border border-stone-800 bg-stone-950 shadow-2xl shadow-black/30">
        <div class="absolute inset-0 opacity-70">
            <div class="absolute -left-24 -top-24 h-80 w-80 rounded-full bg-amber-500/10 blur-3xl"></div>
            <div class="absolute -bottom-32 right-0 h-96 w-96 rounded-full bg-teal-500/10 blur-3xl"></div>
        </div>

        <div class="relative grid gap-8 px-6 py-8 lg:grid-cols-[1.25fr_0.75fr] lg:px-10">
            <div>
                <p class="fb-heading-kicker">
                    Movement Detail
                </p>

                <h1 class="mt-4 text-4xl font-semibold tracking-tight text-stone-100 sm:text-5xl">
                    {{ $movement->title }}
                </h1>

                <p class="mt-5 max-w-2xl text-base leading-7 text-stone-400">
                    {{ $movement->description ?: 'A published Floorbenders movement pathway from the core atlas.' }}
                </p>

                <div class="mt-6 flex flex-wrap items-center gap-3">
                    <x-floorbenders.taxonomy-row :movement="$movement" />

                    <x-floorbenders.note-badge :movement="$movement" label />
                </div>
            </div>

            <div class="fb-panel-soft p-5">
                <p class="text-sm font-medium text-stone-300">
                    Movement Pathway
                </p>

                <div class="mt-5 grid gap-4">
                    <div class="rounded-2xl border border-stone-800 bg-stone-950/60 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">
                            Starts Facing
                        </p>

                        <p class="mt-2 text-lg font-semibold text-stone-100">
                            {{ $movement->start_facing->label() }}
                        </p>

                        @if ($movement->start_position)
                            <p class="mt-1 text-sm text-stone-500">
                                Position: {{ $movement->start_position ?: 'Start position not set'  }}
                            </p>
                        @endif
                    </div>

                    <div class="flex justify-center text-2xl text-amber-400">
                        ↓
                    </div>

                    <div class="rounded-2xl border border-stone-800 bg-stone-950/60 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">
                            Ends Facing
                        </p>

                        <p class="mt-2 text-lg font-semibold text-stone-100">
                            {{ $movement->end_facing->label() }}
                        </p>

                        @if ($movement->end_position)
                            <p class="mt-1 text-sm text-stone-500">
                                Position: {{ $movement->end_position ?: 'End position not set' }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="grid gap-8 lg:grid-cols-[1.45fr_0.55fr]">
        <div class="overflow-hidden rounded-3xl border border-stone-800 bg-black shadow-2xl shadow-black/30">
            <video
                controls
                playsinline
                preload="metadata"
                poster="{{ $mediaAsset->thumbnailUrl() }}"
                class="aspect-video w-full bg-black object-contain"
            >
                <source src="{{ $mediaAsset->cleanVideoUrl() }}" type="video/mp4">

                Your browser does not support the video tag.
            </video>
        </div>

        <aside class="space-y-6">
            <x-ui.panel>
                <x-ui.section-heading
                    title="Realm Identity"
                    :description="$movement->realm->description()"
                />

                <div class="mt-5 flex items-center gap-4">
                    <x-floorbenders.realm-badge :realm="$movement->realm" size="lg" />

                    <div>
                        <p class="text-lg font-semibold text-stone-100">
                            {{ $movement->realm->label() }}
                        </p>

                        <p class="mt-1 text-sm text-stone-500">
                            {{ $movement->realm->metaLabel() }}
                        </p>
                    </div>
                </div>

                <dl class="mt-6 space-y-3 text-sm">
                    <div class="flex items-center justify-between gap-4">
                        <dt class="text-stone-500">Gate</dt>
                        <dd class="font-medium text-stone-200">{{ $movement->gate->label() }}</dd>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <dt class="text-stone-500">Aspect</dt>
                        <dd class="font-medium text-stone-200">{{ $movement->aspect->label() }}</dd>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <dt class="text-stone-500">Orientation</dt>
                        <dd class="font-medium text-stone-200">{{ $movement->realm->orientation()->label() }}</dd>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <dt class="text-stone-500">Layer</dt>
                        <dd class="font-medium text-stone-200">{{ $movement->realm->layer()->label() }}</dd>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <dt class="text-stone-500">Note</dt>
                        <dd class="font-medium text-stone-200">{{ $movement->atlasNote() }}</dd>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <dt class="text-stone-500">Difficulty</dt>
                        <dd class="font-medium text-amber-300">{{ $movement->difficulty->label() }}</dd>
                    </div>
                </dl>
            </x-ui.panel>

            <x-ui.panel soft>
                <x-ui.section-heading
                    title="Preview Assets"
                    description="Generated from the uploaded source video."
                />

                <div class="mt-5 space-y-4">
                    <div>
                        <p class="mb-2 text-sm font-medium text-stone-300">
                            GIF Preview
                        </p>

                        <div class="overflow-hidden rounded-2xl border border-stone-800 bg-stone-950">
                            <img
                                src="{{ $mediaAsset->gifUrl() }}"
                                alt="GIF preview of {{ $movement->title }}"
                                class="aspect-video w-full object-cover"
                                loading="lazy"
                            >
                        </div>
                    </div>

                    <div>
                        <p class="mb-2 text-sm font-medium text-stone-300">
                            Thumbnail
                        </p>

                        <div class="overflow-hidden rounded-2xl border border-stone-800 bg-stone-950">
                            <img
                                src="{{ $mediaAsset->thumbnailUrl() }}"
                                alt="Thumbnail of {{ $movement->title }}"
                                class="aspect-video w-full object-cover"
                                loading="lazy"
                            >
                        </div>
                    </div>
                </div>
            </x-ui.panel>
        </aside>
    </section>

    <section class="grid gap-8 lg:grid-cols-2">
        <x-ui.panel>
            <x-ui.section-heading
                title="Movement Description"
                description="How this pathway functions inside the atlas."
            />

            <div class="mt-5 prose prose-invert max-w-none prose-p:text-stone-300 prose-strong:text-stone-100">
                @if ($movement->description)
                    <p>{{ $movement->description }}</p>
                @else
                    <p class="text-stone-500">
                        No description has been added yet.
                    </p>
                @endif
            </div>
        </x-ui.panel>

        <x-ui.panel>
            <x-ui.section-heading
                title="Teaching Notes"
                description="Instructional cues, safety notes, or movement details."
            />

            <div class="mt-5 prose prose-invert max-w-none prose-p:text-stone-300 prose-strong:text-stone-100">
                @if ($movement->teaching_notes)
                    {!! nl2br(e($movement->teaching_notes)) !!}
                @else
                    <p class="text-stone-500">
                        No teaching notes have been added yet.
                    </p>
                @endif
            </div>
        </x-ui.panel>
    </section>

    <section class="rounded-3xl border border-amber-500/20 bg-amber-500/10 p-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-amber-300">
                    Continue Exploring
                </p>

                <h2 class="mt-2 text-2xl font-semibold tracking-tight text-stone-100">
                    Return to the Movement Atlas
                </h2>

                <p class="mt-2 text-sm text-amber-100/80">
                    Compare this pathway against other gates, aspects, realms, layers, and orientations.
                </p>
            </div>

            <x-ui.button :href="route('library.index')" navigate>
                Browse Atlas
            </x-ui.button>
        </div>
    </section>
</div>
