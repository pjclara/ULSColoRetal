<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;

it('requires authentication to access blocoOperatorioCRS', function () {
    $this->get('/bloco-operatorio-c-rs')
        ->assertRedirect();
});

it('forbids a user without permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->get('/bloco-operatorio-c-rs')
        ->assertForbidden();
});

it('allows a user with view permission', function () {
    $user = User::factory()->create();

    Permission::findOrCreate('bloco-operatorio-c-r.view');

    $user->givePermissionTo('bloco-operatorio-c-r.view');

    $this->actingAs($user);

    $this->get('/bloco-operatorio-c-rs')
        ->assertSuccessful();
});