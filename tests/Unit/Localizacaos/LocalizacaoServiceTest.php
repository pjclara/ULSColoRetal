<?php

use App\Services\LocalizacaoService;

it('has a Localizacao service', function () {
    expect(class_exists(LocalizacaoService::class))->toBeTrue();
});