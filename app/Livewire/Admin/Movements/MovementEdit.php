<?php

namespace App\Livewire\Admin\Movements;

use App\Enums\Difficulty;
use App\Enums\FacingDirection;
use App\Enums\MovementStatus;
use App\Models\Movement;
use Illuminate\Validation\Rule;
use Livewire\Component;

class MovementEdit extends Component
{
    public Movement $movement;

    public string $title = '';

    public string $start_position = '';

    public string $end_position = '';

    public string $start_facing = '';

    public string $end_facing = '';

    public string $difficulty = '';

    public string $status = '';

    public string $description = '';

    public string $teaching_notes = '';

    public int $sort_order = 0;

    public function mount(Movement $movement): void
    {
        $this->movement = $movement->load('mediaAsset');

        $this->title = $movement->title;
        $this->start_position = $movement->start_position ?? '';
        $this->end_position = $movement->end_position ?? '';
        $this->start_facing = $movement->start_facing->value;
        $this->end_facing = $movement->end_facing->value;
        $this->difficulty = $movement->difficulty->value;
        $this->status = $movement->status->value;
        $this->description = $movement->description ?? '';
        $this->teaching_notes = $movement->teaching_notes ?? '';
        $this->sort_order = $movement->sort_order;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'start_position' => ['nullable', 'string', 'max:255'],
            'end_position' => ['nullable', 'string', 'max:255'],
            'start_facing' => ['required', Rule::enum(FacingDirection::class)],
            'end_facing' => ['required', Rule::enum(FacingDirection::class)],
            'difficulty' => ['required', Rule::enum(Difficulty::class)],
            'status' => ['required', Rule::enum(MovementStatus::class)],
            'description' => ['nullable', 'string'],
            'teaching_notes' => ['nullable', 'string'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);

        $this->movement->update($validated);

        session()->flash('status', 'Movement updated.');
    }

    public function render()
    {
        return view('livewire.admin.movements.movement-edit', [
            'difficulties' => Difficulty::cases(),
            'statuses' => MovementStatus::cases(),
            'facingDirections' => FacingDirection::cases(),
        ])->layout('layouts.admin', [
            'title' => "Edit {$this->movement->title}",
        ]);
    }
}
