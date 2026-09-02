<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;

it('requires authentication to access listaDeEspera', function () {
    $this->get('/lista-de-esperas')
        ->assertRedirect();
});

it('forbids a user without permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->get('/lista-de-esperas')
        ->assertForbidden();
});

it('allows a user with view permission', function () {
    $user = User::factory()->create();

    Permission::findOrCreate('lista-de-espera.view');

    $user->givePermissionTo('lista-de-espera.view');

    $this->actingAs($user);

    $this->get('/lista-de-esperas')
        ->assertSuccessful();
});