<?php

use App\Services\DiagnosticoService;

it('has a Diagnostico service', function () {
    expect(class_exists(DiagnosticoService::class))->toBeTrue();
});