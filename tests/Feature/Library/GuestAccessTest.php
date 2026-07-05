<?php

use App\Enums\Aspect;
use App\Enums\Gate;
use App\Enums\MediaProcessingStatus;
use App\Enums\MovementStatus;
use App\Enums\Realm;
use App\Models\Movement;
use App\Models\MovementMediaAsset;

test('guests can view the movement library index', function () {
    $response = $this->get(route('library.index'));

    $response->assertOk();
});

test('guests can view a published movement', function () {
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

    $response = $this->get(route('library.show', $movement));

    $response->assertOk();
    $response->assertSee('Rising Tide');
});
