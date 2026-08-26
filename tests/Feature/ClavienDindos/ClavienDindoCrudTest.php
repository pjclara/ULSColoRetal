<?php

use App\Models\User;

it('prevents unauthorised creation of clavienDindo', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->post('/clavien-dindos', [])
        ->assertForbidden();
});