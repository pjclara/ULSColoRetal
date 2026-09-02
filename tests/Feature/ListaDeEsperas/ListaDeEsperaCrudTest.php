<?php

use App\Models\User;

it('prevents unauthorised creation of listaDeEspera', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->post('/lista-de-esperas', [])
        ->assertForbidden();
});