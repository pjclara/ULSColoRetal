<?php

namespace App\Actions\{SalaDeAgendamento}s;

use App\Models\SalaDeAgendamento;

class DeleteSalaDeAgendamentoAction
{
    public function handle(SalaDeAgendamento $model): void
    {
        $model->delete();
    }
}