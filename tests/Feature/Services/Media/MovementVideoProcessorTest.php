<?php

use App\Enums\Aspect;
use App\Enums\Gate;
use App\Enums\Realm;
use App\Models\Movement;
use App\Models\MovementMediaAsset;
use App\Services\Media\MovementVideoProcessor;
use Illuminate\Support\Facades\Storage;

test('deleteOldOutputs does not throw when clean, gif, and thumbnail paths are null', function () {
    Storage::fake('public');

    $movement = Movement::create([
        'title' => 'Rising Tide',
        'slug' => 'rising-tide',
        'gate' => Gate::VGate,
        'aspect' => Aspect::cases()[0],
        'realm' => Realm::cases()[0],
    ]);

    $mediaAsset = MovementMediaAsset::create([
        'movement_id' => $movement->id,
        'raw_video_path' => 'movements/1/raw/video.mp4',
    ]);

    $processor = new MovementVideoProcessor();
    $method = new ReflectionMethod($processor, 'deleteOldOutputs');
    $method->setAccessible(true);

    $method->invoke($processor, $mediaAsset);
})->throwsNoExceptions();
