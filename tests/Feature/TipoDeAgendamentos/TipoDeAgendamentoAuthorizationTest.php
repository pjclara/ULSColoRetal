<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;

it('requires authentication to access tipoDeAgendamento', function () {
    $this->get('/tipo-de-agendamentos')
        ->assertRedirect();
});

it('forbids a user without permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->get('/tipo-de-agendamentos')
        ->assertForbidden();
});

it('allows a user with view permission', function () {
    $user = User::factory()->create();

    Permission::findOrCreate('tipo-de-agendamento.view');

    $user->givePermissionTo('tipo-de-agendamento.view');

    $this->actingAs($user);

    $this->get('/tipo-de-agendamentos')
        ->assertSuccessful();
});