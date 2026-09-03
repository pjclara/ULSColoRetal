<?php

namespace App\Actions\{LocalDeAgendamento}s;

use App\Models\LocalDeAgendamento;

class DeleteLocalDeAgendamentoAction
{
    public function handle(LocalDeAgendamento $model): void
    {
        $model->delete();
    }
}