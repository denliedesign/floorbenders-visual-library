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
        kicker="Media Processing"
        :title="$movement->title"
        description="Upload raw video, set clean trim points, then generate the public MP4, GIF preview, and thumbnail."
    >
        <x-slot:actions>
            <x-ui.button
                :href="route('admin.movements.edit', $movement)"
                variant="secondary"
                navigate
            >
                Edit Movement
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

    @if ($mediaAsset?->processing_error)
        <x-ui.alert variant="danger">
            {{ $mediaAsset->processing_error }}
        </x-ui.alert>
    @endif

    <section class="grid gap-8 lg:grid-cols-[0.7fr_1.3fr]">
        <aside class="space-y-6">
            <x-ui.panel>
                <x-ui.section-heading
                    title="Movement Slot"
                    description="Taxonomy and publishing details for this movement."
                />

                <div class="mt-5">
                    <x-floorbenders.taxonomy-row :movement="$movement" />
                </div>

                <div class="mt-4">
                    <x-floorbenders.note-badge :movement="$movement" label />
                </div>

                <dl class="mt-6 space-y-3 text-sm">
                    <div class="flex items-center justify-between gap-4">
                        <dt class="text-stone-500">Status</dt>
                        <dd class="font-medium text-stone-200">{{ $movement->status->label() }}</dd>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <dt class="text-stone-500">Difficulty</dt>
                        <dd class="font-medium text-stone-200">{{ $movement->difficulty->label() }}</dd>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <dt class="text-stone-500">Pathway</dt>
                        <dd class="text-right font-medium text-stone-200">
                            {{ $movement->start_position ?: 'Unset' }}
                            →
                            {{ $movement->end_position ?: 'Unset' }}
                        </dd>
                    </div>
                </dl>
            </x-ui.panel>

            <x-ui.panel soft>
                <x-ui.section-heading
                    title="Processing Status"
                    description="The generated assets control public visibility."
                />

                <div class="mt-5 space-y-4">
                    @if ($mediaAsset)
                        <x-ui.badge variant="{{ $mediaAsset->processing_status->value === 'complete' ? 'teal' : 'amber' }}">
                            {{ str($mediaAsset->processing_status->value)->headline() }}
                        </x-ui.badge>

                        <dl class="space-y-3 text-sm">
                            <div class="flex items-center justify-between gap-4">
                                <dt class="text-stone-500">Raw Upload</dt>
                                <dd class="font-medium text-stone-200">
                                    {{ $mediaAsset->raw_video_path ? 'Yes' : 'No' }}
                                </dd>
                            </div>

                            <div class="flex items-center justify-between gap-4">
                                <dt class="text-stone-500">Clean MP4</dt>
                                <dd class="font-medium text-stone-200">
                                    {{ $mediaAsset->clean_video_path ? 'Yes' : 'No' }}
                                </dd>
                            </div>

                            <div class="flex items-center justify-between gap-4">
                                <dt class="text-stone-500">GIF</dt>
                                <dd class="font-medium text-stone-200">
                                    {{ $mediaAsset->gif_path ? 'Yes' : 'No' }}
                                </dd>
                            </div>

                            <div class="flex items-center justify-between gap-4">
                                <dt class="text-stone-500">Thumbnail</dt>
                                <dd class="font-medium text-stone-200">
                                    {{ $mediaAsset->thumbnail_path ? 'Yes' : 'No' }}
                                </dd>
                            </div>

                            @if ($mediaAsset->processed_at)
                                <div class="flex items-center justify-between gap-4">
                                    <dt class="text-stone-500">Processed</dt>
                                    <dd class="font-medium text-stone-200">
                                        {{ $mediaAsset->processed_at->format('M j, Y g:i A') }}
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    @else
                        <x-ui.badge>
                            No Media Uploaded
                        </x-ui.badge>
                    @endif
                </div>
            </x-ui.panel>
        </aside>

        <div class="space-y-6">
            <x-ui.panel>
                <x-ui.section-heading
                    title="1. Upload Raw Video"
                    description="Upload the original clip. It can include setup and walking out of frame; you will trim it below."
                />

                <div class="mt-6 space-y-5">
                    <x-ui.input
                        name="rawVideo"
                        label="Raw Video File"
                        type="file"
                        wire:model="rawVideo"
                        :error="$errors->first('rawVideo')"
                    />

                    <div wire:loading wire:target="rawVideo" class="text-sm text-amber-300">
                        Uploading video...
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <x-ui.button wire:click="saveUpload">
                            Upload Video
                        </x-ui.button>

                        @if ($mediaAsset)
                            <x-ui.button variant="danger" wire:click="removeRawVideo">
                                Remove Media
                            </x-ui.button>
                        @endif
                    </div>
                </div>
            </x-ui.panel>

            @if ($mediaAsset?->raw_video_path)
                <x-ui.panel>
                    <x-ui.section-heading
                        title="2. Preview Raw Video"
                        description="Use this preview to decide the clean start and end timestamps."
                    />

                    <div class="mt-6 overflow-hidden rounded-3xl border border-stone-800 bg-black">
                        <video
                            controls
                            playsinline
                            preload="metadata"
                            class="aspect-video w-full bg-black object-contain"
                        >
                            <source src="{{ $mediaAsset->rawVideoUrl() }}">

                            Your browser does not support the video tag.
                        </video>
                    </div>
                </x-ui.panel>

                <x-ui.panel>
                    <x-ui.section-heading
                        title="3. Set Trim Points"
                        description="Enter the clean start and end time in seconds. Example: 1.25 to 7.80."
                    />

                    <div class="mt-6 grid gap-5 md:grid-cols-2">
                        <x-ui.input
                            name="trim_start_seconds"
                            label="Trim Start Seconds"
                            type="number"
                            step="0.01"
                            min="0"
                            wire:model="trimStartSeconds"
                            :error="$errors->first('trimStartSeconds')"
                        />

                        <x-ui.input
                            name="trim_end_seconds"
                            label="Trim End Seconds"
                            type="number"
                            step="0.01"
                            min="0"
                            wire:model="trimEndSeconds"
                            :error="$errors->first('trimEndSeconds')"
                        />
                    </div>

                    <div class="mt-5 flex flex-wrap gap-3">
                        <x-ui.button wire:click="saveTrimPoints">
                            Save Trim Points
                        </x-ui.button>

                        @if ($mediaAsset->trim_start_seconds !== null && $mediaAsset->trim_end_seconds !== null)
                            <x-ui.button variant="secondary" wire:click="processMedia">
                                Process Media
                            </x-ui.button>
                        @endif
                    </div>
                </x-ui.panel>
            @endif

            @if ($mediaAsset?->clean_video_path || $mediaAsset?->gif_path || $mediaAsset?->thumbnail_path)
                <x-ui.panel>
                    <x-ui.section-heading
                        title="4. Generated Public Assets"
                        description="These files power the public movement atlas and detail page."
                    />

                    <div class="mt-6 grid gap-6 lg:grid-cols-3">
                        @if ($mediaAsset->clean_video_path)
                            <div>
                                <p class="mb-2 text-sm font-medium text-stone-300">
                                    Clean MP4
                                </p>

                                <div class="overflow-hidden rounded-2xl border border-stone-800 bg-black">
                                    <video
                                        controls
                                        playsinline
                                        preload="metadata"
                                        class="aspect-video w-full bg-black object-contain"
                                    >
                                        <source src="{{ $mediaAsset->cleanVideoUrl() }}" type="video/mp4">
                                    </video>
                                </div>
                            </div>
                        @endif

                        @if ($mediaAsset->gif_path)
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
                        @endif

                        @if ($mediaAsset->thumbnail_path)
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
                        @endif
                    </div>
                </x-ui.panel>
            @endif
        </div>
    </section>
</div>
