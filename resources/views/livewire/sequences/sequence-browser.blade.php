<div class="space-y-10">
    <section class="rounded-2xl border border-zinc-200 bg-white p-8 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
        <p class="text-sm font-medium uppercase tracking-wide text-zinc-500">
            Floorbenders Sequences
        </p>

        <div class="mt-3 max-w-3xl">
            <h1 class="text-3xl font-semibold tracking-tight sm:text-4xl">
                Browse published movement sequences.
            </h1>

            <p class="mt-4 text-zinc-600 dark:text-zinc-300">
                Each sequence strings together processed movement clips into a short visual phrase.
            </p>
        </div>
    </section>

    <section class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
        <div class="grid gap-4 md:grid-cols-6">
            <div class="md:col-span-2">
                <label for="search" class="mb-1 block text-sm font-medium">Search</label>
                <input
                    id="search"
                    type="search"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search title, description..."
                    class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950 dark:border-zinc-700 dark:bg-zinc-800"
                >
            </div>
        </div>

        <div class="mt-4 flex justify-end">
            <button
                type="button"
                wire:click="clearFilters"
                class="rounded-lg border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800"
            >
                Clear filters
            </button>
        </div>
    </section>

    <section>
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-xl font-semibold">Published Sequences</h2>

            <p class="text-sm text-zinc-500">
                {{ $sequences->total() }} result{{ $sequences->total() === 1 ? '' : 's' }}
            </p>
        </div>

        @if ($sequences->count())
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($sequences as $sequence)
                    @php
                        $previewMovement = $sequence->sequenceMovements->first()?->movement;
                    @endphp

                    <a
                        href="{{ route('sequences.show', $sequence) }}"
                        wire:navigate
                        class="group overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-zinc-700 dark:bg-zinc-900"
                    >
                        <div class="aspect-video overflow-hidden bg-zinc-950">
                            @if ($previewMovement?->mediaAsset?->gif_path)
                                <img
                                    src="{{ $previewMovement->mediaAsset->gifUrl() }}"
                                    alt="Preview of {{ $sequence->title }}"
                                    class="h-full w-full object-cover transition group-hover:scale-105"
                                    loading="lazy"
                                >
                            @elseif ($previewMovement?->mediaAsset?->thumbnail_path)
                                <img
                                    src="{{ $previewMovement->mediaAsset->thumbnailUrl() }}"
                                    alt="Thumbnail of {{ $sequence->title }}"
                                    class="h-full w-full object-cover transition group-hover:scale-105"
                                    loading="lazy"
                                >
                            @endif
                        </div>

                        <div class="space-y-4 p-5">
                            <div>
                                <h3 class="font-semibold">{{ $sequence->title }}</h3>

                                <p class="mt-1 text-sm text-zinc-500">
                                    {{ $sequence->sequence_movements_count }} movement{{ $sequence->sequence_movements_count === 1 ? '' : 's' }}
                                </p>
                            </div>

                            @if ($sequence->description)
                                <p class="line-clamp-2 text-sm text-zinc-600 dark:text-zinc-300">
                                    {{ $sequence->description }}
                                </p>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $sequences->links() }}
            </div>
        @else
            <div class="rounded-xl border border-dashed border-zinc-300 p-10 text-center dark:border-zinc-700">
                <h3 class="font-medium">No sequences found.</h3>
                <p class="mt-1 text-sm text-zinc-500">
                    Try clearing the search or publishing a sequence from the admin area.
                </p>
            </div>
        @endif
    </section>
</div>
