<?php

use App\Models\User;

it('prevents unauthorised creation of centroDeReferencia', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->post('/centro-de-referencias', [])
        ->assertForbidden();
});