<?php

use App\Models\User;

it('prevents unauthorised creation of diagnostico', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->post('/diagnosticos', [])
        ->assertForbidden();
});