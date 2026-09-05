<?php

use App\Services\IntervencaoService;

it('has a Intervencao service', function () {
    expect(class_exists(IntervencaoService::class))->toBeTrue();
});