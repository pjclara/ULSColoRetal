<?php

use App\Models\User;

it('prevents unauthorised creation of agendamento', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->post('/agendamentos', [])
        ->assertForbidden();
});