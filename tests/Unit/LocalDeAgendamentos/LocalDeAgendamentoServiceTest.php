<?php

use App\Services\LocalDeAgendamentoService;

it('has a LocalDeAgendamento service', function () {
    expect(class_exists(LocalDeAgendamentoService::class))->toBeTrue();
});