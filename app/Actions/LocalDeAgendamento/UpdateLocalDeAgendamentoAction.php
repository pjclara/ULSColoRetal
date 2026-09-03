<?php

namespace App\Actions\{LocalDeAgendamento}s;

use App\Models\LocalDeAgendamento;

class UpdateLocalDeAgendamentoAction
{
    public function handle(LocalDeAgendamento $model, array $data): LocalDeAgendamento
    {
        $model->update($data);

        return $model->refresh();
    }
}