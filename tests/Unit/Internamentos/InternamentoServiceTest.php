<?php

use App\Services\InternamentoService;

it('has a Internamento service', function () {
    expect(class_exists(InternamentoService::class))->toBeTrue();
});