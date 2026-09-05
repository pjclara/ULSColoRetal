<?php

use App\Models\User;

it('prevents unauthorised creation of blocoOperatorioCRS', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->post('/bloco-operatorio-c-rs', [])
        ->assertForbidden();
});