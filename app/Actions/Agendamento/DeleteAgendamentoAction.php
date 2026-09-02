<?php

namespace App\Actions\{Agendamento}s;

use App\Models\Agendamento;

class DeleteAgendamentoAction
{
    public function handle(Agendamento $model): void
    {
        $model->delete();
    }
}