<?php

use App\Models\User;

it('prevents unauthorised creation of origemDoInternamento', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->post('/origem-do-internamentos', [])
        ->assertForbidden();
});