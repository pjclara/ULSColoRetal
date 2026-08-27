<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;

it('requires authentication to access complicacao', function () {
    $this->get('/complicacaos')
        ->assertRedirect();
});

it('forbids a user without permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->get('/complicacaos')
        ->assertForbidden();
});

it('allows a user with view permission', function () {
    $user = User::factory()->create();

    Permission::findOrCreate('complicacao.view');

    $user->givePermissionTo('complicacao.view');

    $this->actingAs($user);

    $this->get('/complicacaos')
        ->assertSuccessful();
});