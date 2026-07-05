<?php

namespace App\Livewire\Admin\Sequences;

use App\Enums\Aspect;
use App\Enums\Difficulty;
use App\Enums\Gate;
use App\Enums\MediaProcessingStatus;
use App\Enums\MovementStatus;
use App\Enums\Realm;
use App\Enums\SequenceStatus;
use App\Enums\RealmLayer;
use App\Enums\RealmOrientation;
use App\Enums\SequenceMediaProcessingStatus;
use App\Jobs\Media\ProcessSequenceVideo;
use App\Models\Movement;
use App\Models\Sequence;
use App\Models\SequenceMovement;
use Illuminate\Validation\Rule;
use Livewire\Component;

class SequenceEdit extends Component
{
    public Sequence $sequence;

    public string $title = '';

    public string $description = '';

    public string $status = '';

    public bool $featured = false;

    public string $movementSearch = '';

    public string $movementGate = '';

    public string $movementAspect = '';

    public string $movementRealm = '';

    public string $movementDifficulty = '';

    public string $movementOrientation = '';

    public string $movementLayer = '';

    public function mount(Sequence $sequence): void
    {
        $this->sequence = $sequence;

        $this->title = $sequence->title;
        $this->description = $sequence->description ?? '';
        $this->status = $sequence->status->value;
        $this->featured = $sequence->featured;
    }

    public function saveDetails(): void
    {
        $validated = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::enum(SequenceStatus::class)],
            'featured' => ['boolean'],
        ]);

        $this->sequence->update($validated);

        session()->flash('status', 'Sequence details saved.');
    }

    public function addMovement(int $movementId): void
    {
        $movement = Movement::query()
            ->published()
            ->withCompletedMedia()
            ->findOrFail($movementId);

        $nextSortOrder = (int) SequenceMovement::query()
                ->where('sequence_id', $this->sequence->id)
                ->max('sort_order') + 1;

        SequenceMovement::create([
            'sequence_id' => $this->sequence->id,
            'movement_id' => $movement->id,
            'sort_order' => $nextSortOrder,
        ]);

        $this->sequence->refresh();

        session()->flash('status', 'Movement added to sequence.');

        $this->sequence->markPhraseMediaStale();
    }

    public function removeMovement(int $sequenceMovementId): void
    {
        SequenceMovement::query()
            ->where('sequence_id', $this->sequence->id)
            ->where('id', $sequenceMovementId)
            ->delete();

        $this->normalizeSortOrder();

        session()->flash('status', 'Movement removed.');

        $this->sequence->markPhraseMediaStale();
    }

    public function moveUp(int $sequenceMovementId): void
    {
        $item = $this->findSequenceMovement($sequenceMovementId);
        $previousItem = SequenceMovement::query()
            ->where('sequence_id', $this->sequence->id)
            ->where('sort_order', '<', $item->sort_order)
            ->orderByDesc('sort_order')
            ->first();

        if (! $previousItem) {
            return;
        }

        $this->swapSortOrder($item, $previousItem);

        $this->sequence->markPhraseMediaStale();
    }

    public function moveDown(int $sequenceMovementId): void
    {
        $item = $this->findSequenceMovement($sequenceMovementId);
        $nextItem = SequenceMovement::query()
            ->where('sequence_id', $this->sequence->id)
            ->where('sort_order', '>', $item->sort_order)
            ->orderBy('sort_order')
            ->first();

        if (! $nextItem) {
            return;
        }

        $this->swapSortOrder($item, $nextItem);

        $this->sequence->markPhraseMediaStale();
    }

    public function generatePhraseMedia(): void
    {
        $this->sequence->load([
            'sequenceMovements' => fn ($query) => $query
                ->with('movement.mediaAsset')
                ->orderBy('sort_order'),
        ]);

        if ($this->sequence->phrase_processing_status === SequenceMediaProcessingStatus::Processing) {
            session()->flash('status', 'Phrase media is already processing.');

            return;
        }

        if ($this->sequence->sequenceMovements->isEmpty()) {
            session()->flash('status', 'Add at least one movement before generating phrase media.');

            return;
        }

        $allMovementsReady = $this->sequence->sequenceMovements->every(function ($sequenceMovement): bool {
            $movement = $sequenceMovement->movement;
            $mediaAsset = $movement?->mediaAsset;

            return $movement
                && $mediaAsset
                && $mediaAsset->processing_status === MediaProcessingStatus::Complete
                && filled($mediaAsset->clean_video_path);
        });

        if (! $allMovementsReady) {
            session()->flash('status', 'Every movement in the phrase needs completed processed media before phrase media can be generated.');

            return;
        }

        $this->sequence->forceFill([
            'phrase_processing_status' => SequenceMediaProcessingStatus::Processing,
            'phrase_processing_error' => null,
            'phrase_media_fingerprint' => null,
        ])->save();

        ProcessSequenceVideo::dispatch($this->sequence->id);

        session()->flash('status', 'Phrase media generation started.');
    }

    protected function findSequenceMovement(int $sequenceMovementId): SequenceMovement
    {
        return SequenceMovement::query()
            ->where('sequence_id', $this->sequence->id)
            ->findOrFail($sequenceMovementId);
    }

    protected function swapSortOrder(SequenceMovement $first, SequenceMovement $second): void
    {
        $firstSort = $first->sort_order;

        $first->update(['sort_order' => $second->sort_order]);
        $second->update(['sort_order' => $firstSort]);

        $this->sequence->refresh();
    }

    protected function normalizeSortOrder(): void
    {
        $items = SequenceMovement::query()
            ->where('sequence_id', $this->sequence->id)
            ->orderBy('sort_order')
            ->get();

        foreach ($items as $index => $item) {
            $item->update(['sort_order' => $index + 1]);
        }

        $this->sequence->refresh();
    }

    public function clearMovementFilters(): void
    {
        $this->reset(['movementSearch', 'movementGate', 'movementAspect', 'movementRealm', 'movementDifficulty', 'movementOrientation', 'movementLayer']);
    }

    public function render()
    {
        $sequenceMovements = $this->sequence
            ->sequenceMovements()
            ->with('movement.mediaAsset')
            ->get();

        $canGeneratePhraseMedia = $sequenceMovements->isNotEmpty()
            && $sequenceMovements->every(function ($sequenceMovement): bool {
                $movement = $sequenceMovement->movement;
                $mediaAsset = $movement?->mediaAsset;

                return $movement
                    && $mediaAsset
                    && $mediaAsset->processing_status === MediaProcessingStatus::Complete
                    && filled($mediaAsset->clean_video_path);
            });

        $estimatedPhraseSeconds = $sequenceMovements
            ->sum(function ($sequenceMovement): float {
                $mediaAsset = $sequenceMovement->movement?->mediaAsset;

                if (
                    ! $mediaAsset
                    || $mediaAsset->trim_start_seconds === null
                    || $mediaAsset->trim_end_seconds === null
                ) {
                    return 0;
                }

                return max(0, $mediaAsset->trim_end_seconds - $mediaAsset->trim_start_seconds);
            });

        $phraseStepWarning = $sequenceMovements->count() > config('floorbenders.media.phrase_max_recommended_steps', 12);

        $phraseDurationWarning = $estimatedPhraseSeconds > config('floorbenders.media.phrase_max_recommended_seconds', 60);

        $availableMovements = Movement::query()
            ->with('mediaAsset')
            ->published()
            ->withCompletedMedia()
            ->when($this->movementSearch, function ($query) {
                $query->where(function ($query) {
                    $query
                        ->where('title', 'like', "%{$this->movementSearch}%")
                        ->orWhere('description', 'like', "%{$this->movementSearch}%")
                        ->orWhere('teaching_notes', 'like', "%{$this->movementSearch}%");
                });
            })
            ->when($this->movementGate, fn ($query) => $query->where('gate', $this->movementGate))
            ->when($this->movementAspect, fn ($query) => $query->where('aspect', $this->movementAspect))
            ->when($this->movementRealm, fn ($query) => $query->where('realm', $this->movementRealm))
            ->when($this->movementDifficulty, fn ($query) => $query->where('difficulty', $this->movementDifficulty))
            ->when($this->movementOrientation, function ($query) {
                $query->whereIn(
                    'realm',
                    collect(Realm::forOrientation($this->movementOrientation))->map->value
                );
            })
            ->when($this->movementLayer, function ($query) {
                $query->whereIn(
                    'realm',
                    collect(Realm::forLayer($this->movementLayer))->map->value
                );
            })
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

        $phraseMediaIsCurrent = $this->sequence->phraseMediaIsCurrent();

        return view('livewire.admin.sequences.sequence-edit', [
            'sequenceMovements' => $sequenceMovements,
            'availableMovements' => $availableMovements,
            'statuses' => SequenceStatus::cases(),
            'gates' => Gate::cases(),
            'aspects' => Aspect::cases(),
            'realms' => Realm::cases(),
            'difficulties' => Difficulty::cases(),
            'orientations' => RealmOrientation::cases(),
            'layers' => RealmLayer::cases(),
            'layerPattern' => $layerPattern,
            'orientationPattern' => $orientationPattern,
            'aspectPattern' => $aspectPattern,
            'realmPattern' => $realmPattern,
            'notePattern' => $notePattern,
            'canGeneratePhraseMedia' => $canGeneratePhraseMedia,
            'estimatedPhraseSeconds' => $estimatedPhraseSeconds,
            'phraseStepWarning' => $phraseStepWarning,
            'phraseDurationWarning' => $phraseDurationWarning,
            'phraseMediaIsCurrent' => $phraseMediaIsCurrent,
        ])->layout('layouts.admin', [
            'title' => "Edit {$this->sequence->title}",
        ]);
    }

    public function clearPhraseMedia(): void
    {
        if ($this->sequence->phrase_processing_status === SequenceMediaProcessingStatus::Processing) {
            session()->flash('status', 'Phrase media is currently processing and cannot be cleared yet.');

            return;
        }

        $this->sequence->clearGeneratedPhraseMedia();

        session()->flash('status', 'Generated phrase media cleared.');
    }
}
