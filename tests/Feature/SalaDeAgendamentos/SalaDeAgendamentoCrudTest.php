<?php

use App\Models\User;

it('prevents unauthorised creation of salaDeAgendamento', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->post('/sala-de-agendamentos', [])
        ->assertForbidden();
});