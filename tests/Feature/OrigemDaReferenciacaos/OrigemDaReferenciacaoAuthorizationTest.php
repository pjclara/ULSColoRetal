<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;

it('requires authentication to access origemDaReferenciacao', function () {
    $this->get('/origem-da-referenciacaos')
        ->assertRedirect();
});

it('forbids a user without permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->get('/origem-da-referenciacaos')
        ->assertForbidden();
});

it('allows a user with view permission', function () {
    $user = User::factory()->create();

    Permission::findOrCreate('origem-da-referenciacao.view');

    $user->givePermissionTo('origem-da-referenciacao.view');

    $this->actingAs($user);

    $this->get('/origem-da-referenciacaos')
        ->assertSuccessful();
});