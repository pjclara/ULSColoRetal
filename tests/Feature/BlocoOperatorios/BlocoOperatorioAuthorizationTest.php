<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;

it('requires authentication to access blocoOperatorio', function () {
    $this->get('/bloco-operatorios')
        ->assertRedirect();
});

it('forbids a user without permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->get('/bloco-operatorios')
        ->assertForbidden();
});

it('allows a user with view permission', function () {
    $user = User::factory()->create();

    Permission::findOrCreate('bloco-operatorio.view');

    $user->givePermissionTo('bloco-operatorio.view');

    $this->actingAs($user);

    $this->get('/bloco-operatorios')
        ->assertSuccessful();
});