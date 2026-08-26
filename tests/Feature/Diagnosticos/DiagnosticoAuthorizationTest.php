<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;

it('requires authentication to access diagnostico', function () {
    $this->get('/diagnosticos')
        ->assertRedirect();
});

it('forbids a user without permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->get('/diagnosticos')
        ->assertForbidden();
});

it('allows a user with view permission', function () {
    $user = User::factory()->create();

    Permission::findOrCreate('diagnostico.view');

    $user->givePermissionTo('diagnostico.view');

    $this->actingAs($user);

    $this->get('/diagnosticos')
        ->assertSuccessful();
});