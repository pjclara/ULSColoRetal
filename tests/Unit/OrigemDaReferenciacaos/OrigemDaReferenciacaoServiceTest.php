<?php

use App\Services\OrigemDaReferenciacaoService;

it('has a OrigemDaReferenciacao service', function () {
    expect(class_exists(OrigemDaReferenciacaoService::class))->toBeTrue();
});