<?php

use App\Services\ListaDeEsperaService;

it('has a ListaDeEspera service', function () {
    expect(class_exists(ListaDeEsperaService::class))->toBeTrue();
});