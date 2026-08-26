<?php

use App\Models\User;

it('prevents unauthorised creation of estadoDaAlta', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->post('/estado-da-altas', [])
        ->assertForbidden();
});