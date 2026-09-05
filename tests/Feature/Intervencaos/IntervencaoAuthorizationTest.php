<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;

it('requires authentication to access intervencao', function () {
    $this->get('/intervencaos')
        ->assertRedirect();
});

it('forbids a user without permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->get('/intervencaos')
        ->assertForbidden();
});

it('allows a user with view permission', function () {
    $user = User::factory()->create();

    Permission::findOrCreate('intervencao.view');

    $user->givePermissionTo('intervencao.view');

    $this->actingAs($user);

    $this->get('/intervencaos')
        ->assertSuccessful();
});