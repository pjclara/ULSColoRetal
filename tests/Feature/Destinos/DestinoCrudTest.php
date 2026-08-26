<?php

use App\Models\User;

it('prevents unauthorised creation of destino', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->post('/destinos', [])
        ->assertForbidden();
});