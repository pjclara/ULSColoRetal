<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;

it('requires authentication to access agendamento', function () {
    $this->get('/agendamentos')
        ->assertRedirect();
});

it('forbids a user without permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->get('/agendamentos')
        ->assertForbidden();
});

it('allows a user with view permission', function () {
    $user = User::factory()->create();

    Permission::findOrCreate('agendamento.view');

    $user->givePermissionTo('agendamento.view');

    $this->actingAs($user);

    $this->get('/agendamentos')
        ->assertSuccessful();
});