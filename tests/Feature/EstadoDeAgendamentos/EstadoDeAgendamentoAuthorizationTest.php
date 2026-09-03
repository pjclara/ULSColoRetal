<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;

it('requires authentication to access estadoDeAgendamento', function () {
    $this->get('/estado-de-agendamentos')
        ->assertRedirect();
});

it('forbids a user without permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->get('/estado-de-agendamentos')
        ->assertForbidden();
});

it('allows a user with view permission', function () {
    $user = User::factory()->create();

    Permission::findOrCreate('estado-de-agendamento.view');

    $user->givePermissionTo('estado-de-agendamento.view');

    $this->actingAs($user);

    $this->get('/estado-de-agendamentos')
        ->assertSuccessful();
});