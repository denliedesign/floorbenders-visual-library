<x-layouts.public :title="__('Welcome')">
    <div class="space-y-10">
        <section class="relative overflow-hidden rounded-3xl border border-stone-800 bg-stone-950 shadow-2xl shadow-black/30">
            <div class="absolute inset-0 opacity-80">
                <div class="absolute -left-28 -top-28 h-96 w-96 rounded-full bg-amber-500/10 blur-3xl"></div>
                <div class="absolute bottom-0 right-0 h-96 w-96 rounded-full bg-teal-500/10 blur-3xl"></div>
                <div class="absolute left-1/2 top-1/2 h-80 w-80 -translate-x-1/2 -translate-y-1/2 rounded-full bg-stone-700/10 blur-3xl"></div>
            </div>

            <div class="grid lg:grid-cols-2">
                <div class="relative gap-10 px-6 py-10 lg:px-10 lg:py-14">
                    <div>
                        <p class="fb-heading-kicker">
                            Floorbenders Visual Library
                        </p>

                        <h1 class="mt-5 max-w-4xl text-5xl font-semibold tracking-tight text-stone-100 sm:text-6xl">
                            A movement atlas for organizing, processing, and exploring Floorbenders video.
                        </h1>

                        <p class="mt-6 max-w-2xl text-lg leading-8 text-stone-400">
                            Floorbenders is a codified floorwork technique. This is a one-of-a-kind searchable video library of the 48 core movements with the ability to sequence them together into pattern-based phrases.
                        </p>

                        <div class="mt-8 flex flex-wrap gap-3">
                            <x-ui.button :href="route('library.index')" navigate>
                                Explore Movement Atlas
                            </x-ui.button>

                            @if ($featuredPhrase)
                                <x-ui.button
                                    :href="route('sequences.show', $featuredPhrase)"
                                    variant="secondary"
                                    navigate
                                >
                                    View Sample Phrase
                                </x-ui.button>
                            @endif

                            @auth
                                @if (auth()->user()->isAdmin())
                                    <x-ui.button :href="route('admin.dashboard')" variant="ghost" navigate>
                                        Admin Console
                                    </x-ui.button>
                                @endif
                            @else
                                <x-ui.button :href="route('login')" variant="ghost" navigate>
                                    Log in
                                </x-ui.button>
                            @endauth
                        </div>
                    </div>
                </div>
                <div class="h-full relative">
                    @if ($featuredPhrase)
                        <video
                            autoplay
                            loop
                            playsinline
                            preload="metadata"
                            poster="{{ $featuredPhrase->phraseThumbnailUrl() }}"
                            class="h-full object-cover"
                        >
                            <source src="{{ $featuredPhrase->phraseVideoUrl() }}" type="video/mp4">
                        </video>
                        <div>
                            <div class="absolute top-4 right-4">
                                <x-ui.badge>Phrase: {{ $featuredPhrase->title }}</x-ui.badge>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <section class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            <x-ui.card hover>
                <p class="fb-heading-kicker">
                    Atlas
                </p>

                <h2 class="mt-3 text-xl font-semibold tracking-tight text-stone-100">
                    Searchable movement library
                </h2>

                <p class="mt-3 text-sm leading-6 text-stone-400">
                    Browse movements by gate, aspect, realm, layer, orientation, and difficulty.
                </p>
            </x-ui.card>

            <x-ui.card hover>
                <p class="fb-heading-kicker">
                    Media
                </p>

                <h2 class="mt-3 text-xl font-semibold tracking-tight text-stone-100">
                    Video processing pipeline
                </h2>

                <p class="mt-3 text-sm leading-6 text-stone-400">
                    Raw uploads become clean MP4 files, GIF previews, and thumbnails for public display.
                </p>
            </x-ui.card>

            <x-ui.card hover>
                <p class="fb-heading-kicker">
                    Taxonomy
                </p>

                <h2 class="mt-3 text-xl font-semibold tracking-tight text-stone-100">
                    Gates, aspects, and realms
                </h2>

                <p class="mt-3 text-sm leading-6 text-stone-400">
                    A compact movement classification system powers filtering, badges, and phrase patterns.
                </p>
            </x-ui.card>

            <x-ui.card hover>
                <p class="fb-heading-kicker">
                    Phrases
                </p>

                <h2 class="mt-3 text-xl font-semibold tracking-tight text-stone-100">
                    Movement path builder
                </h2>

                <p class="mt-3 text-sm leading-6 text-stone-400">
                    Published movement tiles can be arranged, repeated, and studied as phrase paths.
                </p>
            </x-ui.card>
        </section>

        <x-floorbenders.taxonomy-large-key />

        <section class="rounded-3xl border border-amber-500/20 bg-amber-500/10 p-8">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-amber-300">
                        Start Exploring
                    </p>

                    <h2 class="mt-3 text-3xl font-semibold tracking-tight text-stone-100">
                        Open the public Movement Atlas.
                    </h2>

                    <p class="mt-3 max-w-2xl text-sm leading-6 text-amber-100/80">
                        Browse published movement tiles, filter by taxonomy, and open individual movement detail pages with video, GIF, thumbnail, and teaching notes.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <x-ui.button :href="route('library.index')" navigate>
                        Explore Movement Atlas
                    </x-ui.button>

                    @auth
                        @if (auth()->user()->isAdmin())
                            <x-ui.button :href="route('admin.dashboard')" variant="secondary" navigate>
                                Admin Dashboard
                            </x-ui.button>
                        @endif
                    @endauth
                </div>
            </div>
        </section>
    </div>
</x-layouts.public>
