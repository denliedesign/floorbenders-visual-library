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
        kicker="Create Phrase"
        title="Start a new movement phrase."
        description="Create the phrase shell first, then add movement tiles in the Phrase Builder."
    />

    @if (session('status'))
        <x-ui.alert>
            {{ session('status') }}
        </x-ui.alert>
    @endif

    <section class="grid gap-8 lg:grid-cols-[0.7fr_1.3fr]">
        <aside class="space-y-6">
            <x-ui.panel>
                <x-ui.section-heading
                    title="What is a Phrase?"
                    description="A phrase is an ordered pathway made from existing movement tiles."
                />

                <div class="mt-5 space-y-4 text-sm leading-6 text-stone-400">
                    <p>
                        In this portfolio version, phrases work like visual playlists. They show how separate Floorbenders movements can be arranged into a short movement pathway.
                    </p>

                    <p>
                        The clips are not stitched into one seamless video yet. Instead, each movement remains its own clean tile so the structure stays readable.
                    </p>
                </div>
            </x-ui.panel>

            <x-ui.panel soft>
                <x-ui.section-heading
                    title="Phrase Workflow"
                    description="Simple creation flow for this MVP."
                />

                <ol class="mt-5 space-y-3 text-sm text-stone-400">
                    <li class="flex gap-3">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-amber-500 text-xs font-bold text-stone-950">1</span>
                        Create the phrase title.
                    </li>

                    <li class="flex gap-3">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-amber-500 text-xs font-bold text-stone-950">2</span>
                        Add published movement tiles.
                    </li>

                    <li class="flex gap-3">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-amber-500 text-xs font-bold text-stone-950">3</span>
                        Reorder the phrase path.
                    </li>

                    <li class="flex gap-3">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-amber-500 text-xs font-bold text-stone-950">4</span>
                        Publish when ready.
                    </li>
                </ol>
            </x-ui.panel>
        </aside>

        <form wire:submit="save" class="space-y-6">
            <x-ui.panel>
                <x-ui.section-heading
                    title="Phrase Details"
                    description="Set the basic phrase information. You will add movement steps after saving."
                />

                <div class="mt-6 space-y-5">
                    <x-ui.input
                        name="title"
                        label="Title"
                        wire:model="title"
                        :error="$errors->first('title')"
                        placeholder="Example: Grounded to Lifted Pathway"
                    />

                    <x-ui.textarea
                        name="description"
                        label="Description"
                        rows="5"
                        wire:model="description"
                        :error="$errors->first('description')"
                        placeholder="Describe the idea, flow, or teaching purpose of this phrase..."
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
                                    Mark this phrase as a highlighted example.
                                </span>
                            </span>
                        </label>
                    </div>
                </div>
            </x-ui.panel>

            <div class="flex flex-wrap justify-end gap-3">
                <x-ui.button
                    :href="route('admin.sequences.index')"
                    variant="secondary"
                    navigate
                >
                    Cancel
                </x-ui.button>

                <x-ui.button type="submit">
                    Create Phrase
                </x-ui.button>
            </div>
        </form>
    </section>
</div>
