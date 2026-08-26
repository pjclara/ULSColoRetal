<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;

it('requires authentication to access casoSocial', function () {
    $this->get('/caso-socials')
        ->assertRedirect();
});

it('forbids a user without permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->get('/caso-socials')
        ->assertForbidden();
});

it('allows a user with view permission', function () {
    $user = User::factory()->create();

    Permission::findOrCreate('caso-social.view');

    $user->givePermissionTo('caso-social.view');

    $this->actingAs($user);

    $this->get('/caso-socials')
        ->assertSuccessful();
});