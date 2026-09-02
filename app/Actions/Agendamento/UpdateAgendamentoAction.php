<?php

namespace App\Actions\{Agendamento}s;

use App\Models\Agendamento;

class UpdateAgendamentoAction
{
    public function handle(Agendamento $model, array $data): Agendamento
    {
        $model->update($data);

        return $model->refresh();
    }
}