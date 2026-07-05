<?php

namespace App\Livewire\Library;

use App\Enums\Aspect;
use App\Enums\Difficulty;
use App\Enums\Gate;
use App\Enums\Realm;
use App\Enums\RealmLayer;
use App\Enums\RealmOrientation;
use App\Models\Movement;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class MovementBrowser extends Component
{
    use WithPagination;

    public string $search = '';

    public string $gate = '';

    public string $aspect = '';

    public string $realm = '';

    public string $difficulty = '';

    public string $orientation = '';

    public string $layer = '';

    protected array $queryString = [
        'search' => ['except' => ''],
        'gate' => ['except' => ''],
        'aspect' => ['except' => ''],
        'realm' => ['except' => ''],
        'difficulty' => ['except' => ''],
        'orientation' => ['except' => ''],
        'layer' => ['except' => ''],
    ];

    public function updated(string $property): void
    {
        if (in_array($property, [
            'search',
            'gate',
            'aspect',
            'realm',
            'difficulty',
            'orientation',
            'layer',
        ], true)) {
            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->reset([
            'search',
            'gate',
            'aspect',
            'realm',
            'difficulty',
            'orientation',
            'layer',
        ]);

        $this->resetPage();
    }

    public function getHasActiveFiltersProperty(): bool
    {
        return filled($this->search)
            || filled($this->gate)
            || filled($this->aspect)
            || filled($this->realm)
            || filled($this->difficulty)
            || filled($this->orientation)
            || filled($this->layer);
    }

    public function render(): View
    {
        $movements = Movement::query()
            ->with('mediaAsset')
            ->published()
            ->withCompletedMedia()
            ->when($this->search, function ($query): void {
                $query->where(function ($query): void {
                    $query
                        ->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('start_position', 'like', '%' . $this->search . '%')
                        ->orWhere('end_position', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%')
                        ->orWhere('teaching_notes', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->gate, fn ($query) => $query->where('gate', $this->gate))
            ->when($this->aspect, fn ($query) => $query->where('aspect', $this->aspect))
            ->when($this->realm, fn ($query) => $query->where('realm', $this->realm))
            ->when($this->difficulty, fn ($query) => $query->where('difficulty', $this->difficulty))
            ->when($this->orientation, function ($query): void {
                $realmValues = collect(Realm::forOrientation($this->orientation))
                    ->map(fn (Realm $realm) => $realm->value)
                    ->all();

                $query->whereIn('realm', $realmValues);
            })
            ->when($this->layer, function ($query): void {
                $realmValues = collect(Realm::forLayer($this->layer))
                    ->map(fn (Realm $realm) => $realm->value)
                    ->all();

                $query->whereIn('realm', $realmValues);
            })
            ->orderBy('sort_order')
            ->orderBy('title')
            ->paginate(12);

        return view('livewire.library.movement-browser', [
            'movements' => $movements,
            'gates' => Gate::cases(),
            'aspects' => Aspect::cases(),
            'realms' => Realm::cases(),
            'difficulties' => Difficulty::cases(),
            'orientations' => RealmOrientation::cases(),
            'layers' => RealmLayer::cases(),
        ])->layout('layouts.public', [
            'title' => 'Movement Atlas',
        ]);
    }
}
