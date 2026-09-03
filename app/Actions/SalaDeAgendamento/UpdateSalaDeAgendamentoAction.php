<?php

namespace App\Actions\{SalaDeAgendamento}s;

use App\Models\SalaDeAgendamento;

class UpdateSalaDeAgendamentoAction
{
    public function handle(SalaDeAgendamento $model, array $data): SalaDeAgendamento
    {
        $model->update($data);

        return $model->refresh();
    }
}