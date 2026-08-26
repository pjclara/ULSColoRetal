<?php

use App\Models\User;

it('prevents unauthorised creation of internamento', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->post('/internamentos', [])
        ->assertForbidden();
});