<?php

use App\Services\SalaDeAgendamentoService;

it('has a SalaDeAgendamento service', function () {
    expect(class_exists(SalaDeAgendamentoService::class))->toBeTrue();
});