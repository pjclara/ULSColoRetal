<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;

it('requires authentication to access localizacao', function () {
    $this->get('/localizacaos')
        ->assertRedirect();
});

it('forbids a user without permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->get('/localizacaos')
        ->assertForbidden();
});

it('allows a user with view permission', function () {
    $user = User::factory()->create();

    Permission::findOrCreate('localizacao.view');

    $user->givePermissionTo('localizacao.view');

    $this->actingAs($user);

    $this->get('/localizacaos')
        ->assertSuccessful();
});