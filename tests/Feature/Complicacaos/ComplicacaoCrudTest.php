<?php

use App\Models\User;

it('prevents unauthorised creation of complicacao', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->post('/complicacaos', [])
        ->assertForbidden();
});