<?php

use App\Models\User;

it('prevents unauthorised creation of localizacao', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->post('/localizacaos', [])
        ->assertForbidden();
});