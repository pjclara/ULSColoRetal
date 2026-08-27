<?php

use App\Services\ComplicacaoService;

it('has a Complicacao service', function () {
    expect(class_exists(ComplicacaoService::class))->toBeTrue();
});