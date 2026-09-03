<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;

it('requires authentication to access salaDeAgendamento', function () {
    $this->get('/sala-de-agendamentos')
        ->assertRedirect();
});

it('forbids a user without permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->get('/sala-de-agendamentos')
        ->assertForbidden();
});

it('allows a user with view permission', function () {
    $user = User::factory()->create();

    Permission::findOrCreate('sala-de-agendamento.view');

    $user->givePermissionTo('sala-de-agendamento.view');

    $this->actingAs($user);

    $this->get('/sala-de-agendamentos')
        ->assertSuccessful();
});