<?php

use App\Models\User;

it('prevents unauthorised creation of intervencao', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->post('/intervencaos', [])
        ->assertForbidden();
});