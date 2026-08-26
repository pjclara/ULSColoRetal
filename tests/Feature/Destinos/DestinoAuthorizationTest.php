<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;

it('requires authentication to access destino', function () {
    $this->get('/destinos')
        ->assertRedirect();
});

it('forbids a user without permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->get('/destinos')
        ->assertForbidden();
});

it('allows a user with view permission', function () {
    $user = User::factory()->create();

    Permission::findOrCreate('destino.view');

    $user->givePermissionTo('destino.view');

    $this->actingAs($user);

    $this->get('/destinos')
        ->assertSuccessful();
});