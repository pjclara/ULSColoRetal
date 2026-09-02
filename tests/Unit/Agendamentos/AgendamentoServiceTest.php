<?php

use App\Services\AgendamentoService;

it('has a Agendamento service', function () {
    expect(class_exists(AgendamentoService::class))->toBeTrue();
});