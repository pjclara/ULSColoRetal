<?php

use App\Services\UtenteService;

it('has a Utente service', function () {
    expect(class_exists(UtenteService::class))->toBeTrue();
});