<?php

use App\Services\BlocoOperatorioCRSService;

it('has a BlocoOperatorioCRS service', function () {
    expect(class_exists(BlocoOperatorioCRSService::class))->toBeTrue();
});