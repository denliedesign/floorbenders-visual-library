<div class="space-y-8">
    <div>
        <a
            href="{{ route('admin.movements.index') }}"
            wire:navigate
            class="inline-flex items-center gap-2 text-sm font-medium text-stone-400 transition hover:text-amber-300"
        >
            <span aria-hidden="true">←</span>
            Back to Movements
        </a>
    </div>

    <x-ui.page-header
        kicker="Edit Movement"
        :title="$movement->title"
        description="Refine the movement title, positions, publishing status, and teaching information."
    >
        <x-slot:actions>
            <x-ui.button
                :href="route('admin.movements.media', $movement)"
                variant="secondary"
                navigate
            >
                Manage Media
            </x-ui.button>

            @if ($movement->status->value === 'published' && $movement->mediaAsset?->processing_status->value === 'complete')
                <x-ui.button
                    :href="route('library.show', $movement)"
                    variant="secondary"
                    navigate
                >
                    View Public Page
                </x-ui.button>
            @endif
        </x-slot:actions>
    </x-ui.page-header>

    @if (session('status'))
        <x-ui.alert>
            {{ session('status') }}
        </x-ui.alert>
    @endif

    <section class="grid gap-8 lg:grid-cols-[0.65fr_1.35fr]">
        <aside class="space-y-6">
            <x-ui.panel>
                <x-ui.section-heading
                    title="Taxonomy Slot"
                    description="These values define the seeded core pathway and should stay stable."
                />

                <div class="mt-5">
                    <x-floorbenders.taxonomy-row :movement="$movement" />
                </div>

{{--                <div class="mt-4">--}}
{{--                    <x-floorbenders.note-badge :movement="$movement" label />--}}
{{--                </div>--}}

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
                        <dt class="text-stone-500">Realm</dt>
                        <dd class="font-medium text-stone-200">{{ $movement->realm->label() }}</dd>
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
                        <dd class="font-medium text-amber-300">{{ $movement->atlasNote() }}</dd>
                    </div>
                </dl>
            </x-ui.panel>

            <x-ui.panel soft>
                <x-ui.section-heading
                    title="Media Status"
                    description="Upload, trim, and process the movement video from the media screen."
                />

                <div class="mt-5 space-y-3 text-sm">
                    @if ($movement->mediaAsset)
                        <x-ui.badge variant="{{ $movement->mediaAsset->processing_status->value === 'complete' ? 'teal' : 'amber' }}">
                            {{ str($movement->mediaAsset->processing_status->value)->headline() }}
                        </x-ui.badge>

                        @if ($movement->mediaAsset->thumbnail_path)
                            <div class="mt-4 overflow-hidden rounded-2xl border border-stone-800 bg-stone-950">
                                <img
                                    src="{{ $movement->mediaAsset->thumbnailUrl() }}"
                                    alt="Thumbnail of {{ $movement->title }}"
                                    class="aspect-video w-full object-cover"
                                    loading="lazy"
                                >
                            </div>
                        @endif
                    @else
                        <x-ui.badge>
                            No Media Uploaded
                        </x-ui.badge>
                    @endif
                </div>
            </x-ui.panel>
        </aside>

        <form wire:submit="save" class="space-y-6">
            <x-ui.panel>
                <x-ui.section-heading
                    title="Movement Details"
                    description="These fields shape how the movement appears in the public atlas."
                />

                <div class="mt-6 grid gap-5 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <x-ui.input
                            name="title"
                            label="Title"
                            wire:model="title"
                            :error="$errors->first('title')"
                        />
                    </div>

                    <x-ui.input
                        name="start_position"
                        label="Start Position"
                        wire:model="start_position"
                        :error="$errors->first('start_position')"
                    />

                    <x-ui.input
                        name="end_position"
                        label="End Position"
                        wire:model="end_position"
                        :error="$errors->first('end_position')"
                    />

                    <x-ui.select
                        name="start_facing"
                        label="Start Facing"
                        wire:model="start_facing"
                        :error="$errors->first('start_facing')"
                    >
                        <option value="">Not Set</option>

                        @foreach ($facingDirections as $facingDirection)
                            <option value="{{ $facingDirection->value }}">
                                {{ $facingDirection->label() }}
                            </option>
                        @endforeach
                    </x-ui.select>

                    <x-ui.select
                        name="end_facing"
                        label="End Facing"
                        wire:model="end_facing"
                        :error="$errors->first('end_facing')"
                    >
                        <option value="">Not Set</option>

                        @foreach ($facingDirections as $facingDirection)
                            <option value="{{ $facingDirection->value }}">
                                {{ $facingDirection->label() }}
                            </option>
                        @endforeach
                    </x-ui.select>

                    <x-ui.select
                        name="difficulty"
                        label="Difficulty"
                        wire:model="difficulty"
                        :error="$errors->first('difficulty')"
                    >
                        @foreach ($difficulties as $difficultyOption)
                            <option value="{{ $difficultyOption->value }}">
                                {{ $difficultyOption->label() }}
                            </option>
                        @endforeach
                    </x-ui.select>

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

                    <x-ui.input
                        name="sort_order"
                        label="Sort Order"
                        type="number"
                        wire:model="sort_order"
                        :error="$errors->first('sort_order')"
                    />
                </div>
            </x-ui.panel>

            <x-ui.panel>
                <x-ui.section-heading
                    title="Public Copy"
                    description="Short explanation and teaching notes shown on the movement detail page."
                />

                <div class="mt-6 space-y-5">
                    <x-ui.textarea
                        name="description"
                        label="Description"
                        rows="4"
                        wire:model="description"
                        :error="$errors->first('description')"
                    />

                    <x-ui.textarea
                        name="teaching_notes"
                        label="Teaching Notes"
                        rows="8"
                        wire:model="teaching_notes"
                        :error="$errors->first('teaching_notes')"
                    />
                </div>
            </x-ui.panel>

            <div class="flex flex-wrap justify-end gap-3">
                <x-ui.button
                    :href="route('admin.movements.index')"
                    variant="secondary"
                    navigate
                >
                    Cancel
                </x-ui.button>

                <x-ui.button type="submit">
                    Save Movement
                </x-ui.button>
            </div>
        </form>
    </section>
</div>
