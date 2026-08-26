<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;

it('requires authentication to access estadoDaAlta', function () {
    $this->get('/estado-da-altas')
        ->assertRedirect();
});

it('forbids a user without permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->get('/estado-da-altas')
        ->assertForbidden();
});

it('allows a user with view permission', function () {
    $user = User::factory()->create();

    Permission::findOrCreate('estado-da-altum.view');

    $user->givePermissionTo('estado-da-altum.view');

    $this->actingAs($user);

    $this->get('/estado-da-altas')
        ->assertSuccessful();
});