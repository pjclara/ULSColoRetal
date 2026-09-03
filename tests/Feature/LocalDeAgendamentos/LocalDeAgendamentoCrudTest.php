<?php

use App\Models\User;

it('prevents unauthorised creation of localDeAgendamento', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->post('/local-de-agendamentos', [])
        ->assertForbidden();
});