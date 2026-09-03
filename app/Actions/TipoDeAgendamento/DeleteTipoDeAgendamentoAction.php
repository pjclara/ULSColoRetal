<?php

namespace App\Actions\{TipoDeAgendamento}s;

use App\Models\TipoDeAgendamento;

class DeleteTipoDeAgendamentoAction
{
    public function handle(TipoDeAgendamento $model): void
    {
        $model->delete();
    }
}