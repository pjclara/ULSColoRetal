<?php

use App\Models\User;

it('prevents unauthorised creation of tipoDeAgendamento', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->post('/tipo-de-agendamentos', [])
        ->assertForbidden();
});