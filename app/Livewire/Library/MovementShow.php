<?php

namespace App\Livewire\Library;

use App\Models\Movement;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class MovementShow extends Component
{
    public Movement $movement;

    public function mount(Movement $movement): void
    {
        $movement->load('mediaAsset');

        abort_unless(
            $movement->status->value === 'published'
            && $movement->mediaAsset
            && $movement->mediaAsset->processing_status->value === 'complete'
            && $movement->mediaAsset->clean_video_path
            && $movement->mediaAsset->gif_path
            && $movement->mediaAsset->thumbnail_path,
            404
        );

        $this->movement = $movement;
    }

    public function render(): View
    {
        return view('livewire.library.movement-show', [
            'movement' => $this->movement,
            'mediaAsset' => $this->movement->mediaAsset,
        ])->layout('layouts.public', [
            'title' => $this->movement->title,
        ]);
    }
}
