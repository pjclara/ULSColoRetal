<?php

use App\Models\User;

it('prevents unauthorised creation of estadoDeAgendamento', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->post('/estado-de-agendamentos', [])
        ->assertForbidden();
});