<?php

use App\Services\CasoSocialService;

it('has a CasoSocial service', function () {
    expect(class_exists(CasoSocialService::class))->toBeTrue();
});