<?php

use App\Enums\Aspect;
use App\Enums\Gate;
use App\Enums\MediaProcessingStatus;
use App\Enums\MovementStatus;
use App\Enums\Realm;
use App\Enums\SequenceStatus;
use App\Models\Movement;
use App\Models\MovementMediaAsset;
use App\Models\Sequence;
use App\Models\SequenceMovement;

function createSequenceWithMovement(string $title, string $slug, SequenceStatus $status, Gate $gate = Gate::VGate): Sequence
{
    $movement = Movement::create([
        'title' => "{$title} Movement",
        'slug' => "{$slug}-movement",
        'gate' => $gate,
        'aspect' => Aspect::cases()[0],
        'realm' => Realm::cases()[0],
        'status' => MovementStatus::Published,
    ]);

    MovementMediaAsset::create([
        'movement_id' => $movement->id,
        'raw_video_path' => "movements/{$movement->id}/raw/video.mp4",
        'processing_status' => MediaProcessingStatus::Complete,
    ]);

    $sequence = Sequence::create([
        'title' => $title,
        'slug' => $slug,
        'status' => $status,
    ]);

    SequenceMovement::create([
        'sequence_id' => $sequence->id,
        'movement_id' => $movement->id,
        'sort_order' => 1,
    ]);

    return $sequence;
}

test('guests can view the public sequences index', function () {
    createSequenceWithMovement('Opening Phrase', 'opening-phrase', SequenceStatus::Published);

    $response = $this->get(route('sequences.index'));

    $response->assertOk();
    $response->assertSee('Opening Phrase');
});

test('the public sequences index only lists published sequences', function () {
    createSequenceWithMovement('Opening Phrase', 'opening-phrase', SequenceStatus::Published, Gate::VGate);
    createSequenceWithMovement('Draft Phrase', 'draft-phrase', SequenceStatus::Draft, Gate::ZGate);

    $response = $this->get(route('sequences.index'));

    $response->assertOk();
    $response->assertSee('Opening Phrase');
    $response->assertDontSee('Draft Phrase');
});

test('the sequences index route is public, not the admin management route', function () {
    expect(route('sequences.index'))->not->toContain('/admin/');
});
