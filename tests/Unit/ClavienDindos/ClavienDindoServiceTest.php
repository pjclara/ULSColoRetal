<?php

use App\Services\ClavienDindoService;

it('has a ClavienDindo service', function () {
    expect(class_exists(ClavienDindoService::class))->toBeTrue();
});