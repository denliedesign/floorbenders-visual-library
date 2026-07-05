<?php

use App\Enums\Aspect;
use App\Enums\Gate;
use App\Enums\MovementStatus;
use App\Enums\Realm;
use App\Livewire\Admin\Media\MovementMediaManager;
use App\Models\Movement;
use App\Models\MovementMediaAsset;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

test('admin can view the movement media page', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['role' => 'admin'])->save();

    $movement = Movement::create([
        'title' => 'Rising Tide',
        'slug' => 'rising-tide',
        'gate' => Gate::VGate,
        'aspect' => Aspect::cases()[0],
        'realm' => Realm::cases()[0],
        'status' => MovementStatus::Draft,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.movements.media', $movement));

    $response->assertOk();
    $response->assertSee('Media Processing');
});

test('raw video uploads over the configured max size are rejected', function () {
    Storage::fake('public');

    $admin = User::factory()->create();
    $admin->forceFill(['role' => 'admin'])->save();

    $movement = Movement::create([
        'title' => 'Rising Tide',
        'slug' => 'rising-tide',
        'gate' => Gate::VGate,
        'aspect' => Aspect::cases()[0],
        'realm' => Realm::cases()[0],
        'status' => MovementStatus::Draft,
    ])->refresh();

    $component = Livewire::actingAs($admin)
        ->test(MovementMediaManager::class, ['movement' => $movement])
        ->set('rawVideo', UploadedFile::fake()->create('clip.mp4', 61441, 'video/mp4'));

    $component->assertHasErrors('rawVideo');
    expect($component->errors()->first('rawVideo'))->toContain('61440 kilobytes');
});

test('raw video uploads within the configured max size are accepted', function () {
    Storage::fake('public');

    $admin = User::factory()->create();
    $admin->forceFill(['role' => 'admin'])->save();

    $movement = Movement::create([
        'title' => 'Rising Tide',
        'slug' => 'rising-tide',
        'gate' => Gate::VGate,
        'aspect' => Aspect::cases()[0],
        'realm' => Realm::cases()[0],
        'status' => MovementStatus::Draft,
    ])->refresh();

    Livewire::actingAs($admin)
        ->test(MovementMediaManager::class, ['movement' => $movement])
        ->set('rawVideo', UploadedFile::fake()->create('clip.mp4', 61440, 'video/mp4'))
        ->call('saveUpload')
        ->assertHasNoErrors();

    expect($movement->fresh()->mediaAsset)->not->toBeNull();
});

test('removing media before it has been processed does not throw', function () {
    Storage::fake('public');

    $admin = User::factory()->create();
    $admin->forceFill(['role' => 'admin'])->save();

    $movement = Movement::create([
        'title' => 'Rising Tide',
        'slug' => 'rising-tide',
        'gate' => Gate::VGate,
        'aspect' => Aspect::cases()[0],
        'realm' => Realm::cases()[0],
        'status' => MovementStatus::Draft,
    ])->refresh();

    MovementMediaAsset::create([
        'movement_id' => $movement->id,
        'raw_video_path' => 'movements/1/raw/video.mp4',
    ]);

    Livewire::actingAs($admin)
        ->test(MovementMediaManager::class, ['movement' => $movement])
        ->call('removeRawVideo')
        ->assertHasNoErrors();

    expect($movement->fresh()->mediaAsset)->toBeNull();
});
