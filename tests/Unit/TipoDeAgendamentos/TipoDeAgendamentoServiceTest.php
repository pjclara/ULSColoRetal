<?php

use App\Services\TipoDeAgendamentoService;

it('has a TipoDeAgendamento service', function () {
    expect(class_exists(TipoDeAgendamentoService::class))->toBeTrue();
});