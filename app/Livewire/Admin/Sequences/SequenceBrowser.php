<?php

namespace App\Livewire\Admin\Sequences;

use App\Models\Sequence;
use Livewire\Component;
use Livewire\WithPagination;

class SequenceBrowser extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search']);
        $this->resetPage();
    }

    public function render()
    {
        $sequences = Sequence::query()
            ->published()
            ->withCount('sequenceMovements')
            ->with('sequenceMovements.movement.mediaAsset')
            ->when($this->search, function ($query) {
                $query
                    ->where('title', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%");
            })
            ->latest()
            ->paginate(12);

        return view('livewire.sequences.sequence-browser', [
            'sequences' => $sequences,
        ])->layout('layouts.app', [
            'title' => 'Sequences',
        ]);
    }
}
