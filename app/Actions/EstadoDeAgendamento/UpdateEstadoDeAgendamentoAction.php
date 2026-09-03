<?php

namespace App\Actions\{EstadoDeAgendamento}s;

use App\Models\EstadoDeAgendamento;

class UpdateEstadoDeAgendamentoAction
{
    public function handle(EstadoDeAgendamento $model, array $data): EstadoDeAgendamento
    {
        $model->update($data);

        return $model->refresh();
    }
}