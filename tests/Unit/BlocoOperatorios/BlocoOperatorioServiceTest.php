<?php

use App\Services\BlocoOperatorioService;

it('has a BlocoOperatorio service', function () {
    expect(class_exists(BlocoOperatorioService::class))->toBeTrue();
});