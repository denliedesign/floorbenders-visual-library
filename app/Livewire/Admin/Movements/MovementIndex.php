<?php

namespace App\Livewire\Admin\Movements;

use App\Enums\Aspect;
use App\Enums\Gate;
use App\Enums\MovementStatus;
use App\Enums\Realm;
use App\Models\Movement;
use Livewire\Component;
use Livewire\WithPagination;

class MovementIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $gate = '';

    public string $aspect = '';

    public string $realm = '';

    public string $status = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedGate(): void
    {
        $this->resetPage();
    }

    public function updatedAspect(): void
    {
        $this->resetPage();
    }

    public function updatedRealm(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset([
            'search',
            'gate',
            'aspect',
            'realm',
            'status',
        ]);

        $this->resetPage();
    }

    public function render()
    {
        $movements = Movement::query()
            ->with('mediaAsset')
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query
                        ->where('title', 'like', "%{$this->search}%")
                        ->orWhere('description', 'like', "%{$this->search}%")
                        ->orWhere('teaching_notes', 'like', "%{$this->search}%")
                        ->orWhere('start_position', 'like', "%{$this->search}%")
                        ->orWhere('end_position', 'like', "%{$this->search}%");
                });
            })
            ->when($this->gate, fn ($query) => $query->where('gate', $this->gate))
            ->when($this->aspect, fn ($query) => $query->where('aspect', $this->aspect))
            ->when($this->realm, fn ($query) => $query->where('realm', $this->realm))
            ->when($this->status, fn ($query) => $query->where('status', $this->status))
            ->orderBy('sort_order')
            ->paginate(12);

        return view('livewire.admin.movements.movement-index', [
            'movements' => $movements,
            'gates' => Gate::cases(),
            'aspects' => Aspect::cases(),
            'realms' => Realm::cases(),
            'statuses' => MovementStatus::cases(),
        ])->layout('layouts.admin', [
            'title' => 'Manage Movements',
        ]);
    }
}
