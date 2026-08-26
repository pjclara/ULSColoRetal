<?php

use App\Services\OrigemDoInternamentoService;

it('has a OrigemDoInternamento service', function () {
    expect(class_exists(OrigemDoInternamentoService::class))->toBeTrue();
});