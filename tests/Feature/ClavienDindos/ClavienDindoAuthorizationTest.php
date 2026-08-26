<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;

it('requires authentication to access clavienDindo', function () {
    $this->get('/clavien-dindos')
        ->assertRedirect();
});

it('forbids a user without permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->get('/clavien-dindos')
        ->assertForbidden();
});

it('allows a user with view permission', function () {
    $user = User::factory()->create();

    Permission::findOrCreate('clavien-dindo.view');

    $user->givePermissionTo('clavien-dindo.view');

    $this->actingAs($user);

    $this->get('/clavien-dindos')
        ->assertSuccessful();
});