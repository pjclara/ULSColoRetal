<?php

use App\Models\User;

it('prevents unauthorised creation of blocoOperatorio', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->post('/bloco-operatorios', [])
        ->assertForbidden();
});