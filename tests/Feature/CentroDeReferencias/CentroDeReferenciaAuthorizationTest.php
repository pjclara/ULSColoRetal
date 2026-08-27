<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;

it('requires authentication to access centroDeReferencia', function () {
    $this->get('/centro-de-referencias')
        ->assertRedirect();
});

it('forbids a user without permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->get('/centro-de-referencias')
        ->assertForbidden();
});

it('allows a user with view permission', function () {
    $user = User::factory()->create();

    Permission::findOrCreate('centro-de-referencium.view');

    $user->givePermissionTo('centro-de-referencium.view');

    $this->actingAs($user);

    $this->get('/centro-de-referencias')
        ->assertSuccessful();
});