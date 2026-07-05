@props([
    'movement',
    'href' => null,
    'actionLabel' => null,
    'action' => null,
])

@php
    $content = trim($slot);
@endphp

<div {{ $attributes->class('fb-card fb-card-hover overflow-hidden') }}>
    <div class="aspect-video overflow-hidden bg-stone-950">
        @if ($movement->mediaAsset?->gif_path)
            <img
                src="{{ $movement->mediaAsset->gifUrl() }}"
                alt="Preview of {{ $movement->title }}"
                class="h-full w-full object-cover transition duration-300 hover:scale-105"
                loading="lazy"
            >
        @elseif ($movement->mediaAsset?->thumbnail_path)
            <img
                src="{{ $movement->mediaAsset->thumbnailUrl() }}"
                alt="Thumbnail of {{ $movement->title }}"
                class="h-full w-full object-cover transition duration-300 hover:scale-105"
                loading="lazy"
            >
        @else
            <div class="flex h-full w-full items-center justify-center text-sm text-stone-600">
                No preview
            </div>
        @endif
    </div>

    <div class="space-y-4 p-4">
        <div>
            <div class="flex items-start justify-between gap-3">
                <div>
                    @if ($href)
                        <a href="{{ $href }}" wire:navigate class="font-semibold text-stone-100 hover:text-amber-300">
                            {{ $movement->title }}
                        </a>
                    @else
                        <h3 class="font-semibold text-stone-100">
                            {{ $movement->title }}
                        </h3>
                    @endif
                </div>

                <x-floorbenders.note-badge :movement="$movement" />
            </div>

            <p class="mt-1 text-sm text-stone-500">
                {{ $movement->start_position ?: 'Start not set' }}
                →
                {{ $movement->end_position ?: 'End not set' }}
            </p>
        </div>

        <x-floorbenders.taxonomy-row :movement="$movement" compact />

        @if ($content)
            <div>
                {{ $slot }}
            </div>
        @endif

        @if ($actionLabel && $action)
            <button
                type="button"
                wire:click="{{ $action }}"
                class="fb-button-primary w-full"
            >
                {{ $actionLabel }}
            </button>
        @endif
    </div>
</div>
