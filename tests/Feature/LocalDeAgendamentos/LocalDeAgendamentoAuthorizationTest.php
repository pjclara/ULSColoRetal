<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;

it('requires authentication to access localDeAgendamento', function () {
    $this->get('/local-de-agendamentos')
        ->assertRedirect();
});

it('forbids a user without permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->get('/local-de-agendamentos')
        ->assertForbidden();
});

it('allows a user with view permission', function () {
    $user = User::factory()->create();

    Permission::findOrCreate('local-de-agendamento.view');

    $user->givePermissionTo('local-de-agendamento.view');

    $this->actingAs($user);

    $this->get('/local-de-agendamentos')
        ->assertSuccessful();
});