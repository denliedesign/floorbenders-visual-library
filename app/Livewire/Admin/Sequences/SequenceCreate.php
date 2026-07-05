<?php

namespace App\Livewire\Admin\Sequences;

use App\Enums\SequenceStatus;
use App\Models\Sequence;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;

class SequenceCreate extends Component
{
    public string $title = '';

    public string $description = '';

    public string $status = 'draft';

    public bool $featured = false;

    public function save()
    {
        $validated = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::enum(SequenceStatus::class)],
            'featured' => ['boolean'],
        ]);

        $sequence = Sequence::create([
            ...$validated,
            'slug' => Str::slug($validated['title']) . '-' . Str::lower(Str::random(5)),
        ]);

        return $this->redirectRoute('admin.sequences.edit', $sequence, navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.sequences.sequence-create', [
            'statuses' => SequenceStatus::cases(),
        ])->layout('layouts.admin', [
            'title' => 'Create Sequence',
        ]);
    }
}
