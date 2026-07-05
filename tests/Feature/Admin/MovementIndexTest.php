<?php

use App\Enums\Aspect;
use App\Enums\Gate;
use App\Enums\Realm;
use App\Models\Movement;
use App\Models\User;

test('admin can view the movements index page', function () {
    $admin = User::factory()->create();
    $admin->forceFill(['role' => 'admin'])->save();

    Movement::create([
        'title' => 'Rising Tide',
        'slug' => 'rising-tide',
        'gate' => Gate::VGate,
        'aspect' => Aspect::cases()[0],
        'realm' => Realm::cases()[0],
    ]);

    $response = $this->actingAs($admin)->get(route('admin.movements.index'));

    $response->assertOk();
    $response->assertSee('Manage Movements');
    $response->assertSee('Rising Tide');
});

test('non-admin cannot view the movements index page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('admin.movements.index'));

    $response->assertForbidden();
});
