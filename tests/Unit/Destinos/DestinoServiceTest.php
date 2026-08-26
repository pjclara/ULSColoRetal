<?php

use App\Services\DestinoService;

it('has a Destino service', function () {
    expect(class_exists(DestinoService::class))->toBeTrue();
});