<?php

namespace App\Livewire\Sequences;

use App\Enums\SequenceStatus;
use App\Models\Sequence;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class SequenceIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $preview = '';

    public int $perPage = 12;

    protected array $queryString = [
        'search' => ['except' => ''],
        'preview' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPreview(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset([
            'search',
            'preview',
        ]);

        $this->resetPage();
    }

    public function render(): View
    {
        $query = Sequence::query()
            ->with([
                'sequenceMovements' => fn ($query) => $query
                    ->with('movement.mediaAsset')
                    ->orderBy('sort_order'),
            ])
            ->where('status', SequenceStatus::Published)
            ->when($this->search, function ($query): void {
                $query->where(function ($query): void {
                    $query
                        ->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->preview === 'featured', fn ($query) => $query->where('featured', true))
            ->latest();

        $sequences = $query->paginate($this->perPage);

        $publishedSequences = Sequence::query()
            ->with([
                'sequenceMovements' => fn ($query) => $query
                    ->with('movement.mediaAsset')
                    ->orderBy('sort_order'),
            ])
            ->where('status', SequenceStatus::Published)
            ->get();

        return view('livewire.sequences.sequence-index', [
            'sequences' => $sequences,
            'publishedPhraseCount' => $publishedSequences->count(),
            'generatedPhraseMediaCount' => $publishedSequences->filter->hasGeneratedPhraseMedia()->count(),
            'featuredPhraseCount' => $publishedSequences->where('featured', true)->count(),
        ])->layout('layouts.public', [
            'title' => 'Phrase Builder',
        ]);
    }
}
