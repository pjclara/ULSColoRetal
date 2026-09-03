<?php

namespace App\Actions\{LocalDeAgendamento}s;

use App\Models\LocalDeAgendamento;

class CreateLocalDeAgendamentoAction
{
    public function handle(array $data): LocalDeAgendamento
    {
        return LocalDeAgendamento::create($data);
    }
}