<?php

namespace App\Actions\{EstadoDeAgendamento}s;

use App\Models\EstadoDeAgendamento;

class CreateEstadoDeAgendamentoAction
{
    public function handle(array $data): EstadoDeAgendamento
    {
        return EstadoDeAgendamento::create($data);
    }
}