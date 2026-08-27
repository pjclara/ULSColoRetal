<?php

use App\Services\CentroDeReferenciaService;

it('has a CentroDeReferencia service', function () {
    expect(class_exists(CentroDeReferenciaService::class))->toBeTrue();
});