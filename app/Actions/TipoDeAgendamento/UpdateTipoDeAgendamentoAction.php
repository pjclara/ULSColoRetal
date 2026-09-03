<?php

namespace App\Actions\{TipoDeAgendamento}s;

use App\Models\TipoDeAgendamento;

class UpdateTipoDeAgendamentoAction
{
    public function handle(TipoDeAgendamento $model, array $data): TipoDeAgendamento
    {
        $model->update($data);

        return $model->refresh();
    }
}