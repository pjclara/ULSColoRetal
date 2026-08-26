<?php

use App\Models\User;

it('prevents unauthorised creation of casoSocial', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->post('/caso-socials', [])
        ->assertForbidden();
});