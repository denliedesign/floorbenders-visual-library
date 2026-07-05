<?php

namespace App\Livewire\Admin\Sequences;

use App\Enums\SequenceMediaProcessingStatus;
use App\Enums\SequenceStatus;
use App\Models\Sequence;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class SequenceIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $status = '';

    public string $mediaStatus = '';

    public string $featured = '';

    public int $perPage = 12;

    protected array $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'mediaStatus' => ['except' => ''],
        'featured' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingMediaStatus(): void
    {
        $this->resetPage();
    }

    public function updatingFeatured(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset([
            'search',
            'status',
            'mediaStatus',
            'featured',
        ]);

        $this->resetPage();
    }

    public function render(): View
    {
        $baseQuery = Sequence::query()
            ->with([
                'sequenceMovements' => fn ($query) => $query
                    ->with('movement.mediaAsset')
                    ->orderBy('sort_order'),
            ]);

        $sequences = (clone $baseQuery)
            ->when($this->search, function ($query): void {
                $query->where(function ($query): void {
                    $query
                        ->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status, fn ($query) => $query->where('status', $this->status))
            ->when($this->mediaStatus, fn ($query) => $query->where('phrase_processing_status', $this->mediaStatus))
            ->when($this->featured !== '', fn ($query) => $query->where('featured', (bool) $this->featured))
            ->latest()
            ->paginate($this->perPage);

        $summarySequences = $baseQuery->get();

        $generatedPhraseMediaCount = $summarySequences
            ->filter
            ->hasGeneratedPhraseMedia()
            ->count();

        $outdatedPhraseMediaCount = $summarySequences
            ->filter(function (Sequence $sequence): bool {
                return $sequence->phrase_processing_status === SequenceMediaProcessingStatus::Stale
                    || (
                        $sequence->phrase_processing_status === SequenceMediaProcessingStatus::Complete
                        && ! $sequence->phraseMediaIsCurrent()
                    );
            })
            ->count();

        $failedPhraseMediaCount = $summarySequences
            ->where('phrase_processing_status', SequenceMediaProcessingStatus::Failed)
            ->count();

        $processingPhraseMediaCount = $summarySequences
            ->where('phrase_processing_status', SequenceMediaProcessingStatus::Processing)
            ->count();

        return view('livewire.admin.sequences.sequence-index', [
            'sequences' => $sequences,
            'statuses' => SequenceStatus::cases(),
            'mediaStatuses' => SequenceMediaProcessingStatus::cases(),

            'totalPhraseCount' => $summarySequences->count(),
            'publishedPhraseCount' => $summarySequences->where('status', SequenceStatus::Published)->count(),
            'featuredPhraseCount' => $summarySequences->where('featured', true)->count(),
            'generatedPhraseMediaCount' => $generatedPhraseMediaCount,
            'outdatedPhraseMediaCount' => $outdatedPhraseMediaCount,
            'failedPhraseMediaCount' => $failedPhraseMediaCount,
            'processingPhraseMediaCount' => $processingPhraseMediaCount,
        ])->layout('layouts.admin', [
            'title' => 'Phrase Builder',
        ]);
    }
}
