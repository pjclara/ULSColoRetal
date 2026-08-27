<?php

use App\Models\User;

it('prevents unauthorised creation of origemDaReferenciacao', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->post('/origem-da-referenciacaos', [])
        ->assertForbidden();
});