<?php

namespace App\Livewire\Sequences;

use App\Enums\MediaProcessingStatus;
use App\Enums\MovementStatus;
use App\Enums\SequenceStatus;
use App\Models\Sequence;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class SequenceShow extends Component
{
    public Sequence $sequence;

    public function mount(Sequence $sequence): void
    {
        $sequence->load([
            'sequenceMovements' => fn ($query) => $query
                ->with('movement.mediaAsset')
                ->orderBy('sort_order'),
        ]);

        abort_unless(
            $sequence->status === SequenceStatus::Published,
            404
        );

        $hasOnlyPublicMovements = $sequence->sequenceMovements->every(function ($sequenceMovement): bool {
            $movement = $sequenceMovement->movement;
            $mediaAsset = $movement?->mediaAsset;

            return $movement
                && $movement->status === MovementStatus::Published
                && $mediaAsset
                && $mediaAsset->processing_status === MediaProcessingStatus::Complete
                && $mediaAsset->clean_video_path
                && $mediaAsset->gif_path
                && $mediaAsset->thumbnail_path;
        });

        abort_unless($hasOnlyPublicMovements, 404);

        $this->sequence = $sequence;
    }

    public function render(): View
    {
        $sequenceMovements = $this->sequence
            ->sequenceMovements()
            ->with('movement.mediaAsset')
            ->orderBy('sort_order')
            ->get();

        $layerPattern = $sequenceMovements
            ->map(fn ($item) => $item->movement->realm->layer()->label())
            ->implode(' → ');

        $orientationPattern = $sequenceMovements
            ->map(fn ($item) => $item->movement->realm->orientation()->label())
            ->implode(' → ');

        $aspectPattern = $sequenceMovements
            ->map(fn ($item) => $item->movement->aspect->label())
            ->implode(' → ');

        $realmPattern = $sequenceMovements
            ->map(fn ($item) => $item->movement->realm->label())
            ->implode(' → ');

        $notePattern = $sequenceMovements
            ->map(fn ($item) => $item->movement->atlasNote())
            ->implode(' → ');

        $hasGeneratedPhraseMedia = $this->sequence->hasGeneratedPhraseMedia();

        return view('livewire.sequences.sequence-show', [
            'sequence' => $this->sequence,
            'sequenceMovements' => $sequenceMovements,
            'layerPattern' => $layerPattern,
            'orientationPattern' => $orientationPattern,
            'aspectPattern' => $aspectPattern,
            'realmPattern' => $realmPattern,
            'notePattern' => $notePattern,
            'hasGeneratedPhraseMedia' => $hasGeneratedPhraseMedia,
        ])->layout('layouts.public', [
            'title' => $this->sequence->title,
        ]);
    }
}
