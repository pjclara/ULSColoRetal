<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;

it('requires authentication to access origemDoInternamento', function () {
    $this->get('/origem-do-internamentos')
        ->assertRedirect();
});

it('forbids a user without permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->get('/origem-do-internamentos')
        ->assertForbidden();
});

it('allows a user with view permission', function () {
    $user = User::factory()->create();

    Permission::findOrCreate('origem-do-internamento.view');

    $user->givePermissionTo('origem-do-internamento.view');

    $this->actingAs($user);

    $this->get('/origem-do-internamentos')
        ->assertSuccessful();
});