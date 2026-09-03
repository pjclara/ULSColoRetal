<?php

use App\Services\EstadoDeAgendamentoService;

it('has a EstadoDeAgendamento service', function () {
    expect(class_exists(EstadoDeAgendamentoService::class))->toBeTrue();
});