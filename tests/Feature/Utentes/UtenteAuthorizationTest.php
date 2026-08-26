<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;

it('requires authentication to access utente', function () {
    $this->get('/utentes')
        ->assertRedirect();
});

it('forbids a user without permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->get('/utentes')
        ->assertForbidden();
});

it('allows a user with view permission', function () {
    $user = User::factory()->create();

    Permission::findOrCreate('utente.view');

    $user->givePermissionTo('utente.view');

    $this->actingAs($user);

    $this->get('/utentes')
        ->assertSuccessful();
});