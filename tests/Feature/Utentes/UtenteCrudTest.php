<?php

use App\Models\User;

it('prevents unauthorised creation of utente', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->post('/utentes', [])
        ->assertForbidden();
});