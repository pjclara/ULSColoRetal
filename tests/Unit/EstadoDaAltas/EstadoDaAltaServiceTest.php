<?php

use App\Services\EstadoDaAltaService;

it('has a EstadoDaAlta service', function () {
    expect(class_exists(EstadoDaAltaService::class))->toBeTrue();
});