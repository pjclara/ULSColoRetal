<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;

it('requires authentication to access internamento', function () {
    $this->get('/internamentos')
        ->assertRedirect();
});

it('forbids a user without permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->get('/internamentos')
        ->assertForbidden();
});

it('allows a user with view permission', function () {
    $user = User::factory()->create();

    Permission::findOrCreate('internamento.view');

    $user->givePermissionTo('internamento.view');

    $this->actingAs($user);

    $this->get('/internamentos')
        ->assertSuccessful();
});