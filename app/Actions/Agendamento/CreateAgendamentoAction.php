<?php

namespace App\Actions\{Agendamento}s;

use App\Models\Agendamento;

class CreateAgendamentoAction
{
    public function handle(array $data): Agendamento
    {
        return Agendamento::create($data);
    }
}