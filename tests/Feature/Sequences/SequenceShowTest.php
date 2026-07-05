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
use App\Models\User;

function createPublishableSequence(): Sequence
{
    $movement = Movement::create([
        'title' => 'Rising Tide',
        'slug' => 'rising-tide',
        'gate' => Gate::VGate,
        'aspect' => Aspect::cases()[0],
        'realm' => Realm::cases()[0],
        'status' => MovementStatus::Published,
    ]);

    MovementMediaAsset::create([
        'movement_id' => $movement->id,
        'raw_video_path' => 'movements/1/raw/video.mp4',
        'clean_video_path' => 'movements/1/processed/rising-tide-clean.mp4',
        'thumbnail_path' => 'movements/1/processed/rising-tide-thumbnail.jpg',
        'processing_status' => MediaProcessingStatus::Complete,
    ]);

    $sequence = Sequence::create([
        'title' => 'Opening Phrase',
        'slug' => 'opening-phrase',
        'status' => SequenceStatus::Published,
    ]);

    SequenceMovement::create([
        'sequence_id' => $sequence->id,
        'movement_id' => $movement->id,
        'sort_order' => 1,
    ]);

    return $sequence;
}

test('the sequences.show route is registered outside the admin route group', function () {
    $sequence = createPublishableSequence();

    expect(route('sequences.show', $sequence))
        ->not->toContain('/admin/');
});

test('an authenticated user can view a published sequence', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['role' => 'admin'])->save();

    $sequence = createPublishableSequence();

    $response = $this->actingAs($admin)->get(route('sequences.show', $sequence));

    $response->assertOk();
    $response->assertSee('Opening Phrase');
    $response->assertSee('Rising Tide');
});

test('guests can view a published sequence', function () {
    $sequence = createPublishableSequence();

    $response = $this->get(route('sequences.show', $sequence));

    $response->assertOk();
    $response->assertSee('Opening Phrase');
    $response->assertSee('Rising Tide');
});

test('a draft sequence is not viewable', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['role' => 'admin'])->save();

    $sequence = createPublishableSequence();
    $sequence->update(['status' => SequenceStatus::Draft]);

    $response = $this->actingAs($admin)->get(route('sequences.show', $sequence));

    $response->assertNotFound();
});
