<?php

namespace App\Actions\{EstadoDeAgendamento}s;

use App\Models\EstadoDeAgendamento;

class DeleteEstadoDeAgendamentoAction
{
    public function handle(EstadoDeAgendamento $model): void
    {
        $model->delete();
    }
}