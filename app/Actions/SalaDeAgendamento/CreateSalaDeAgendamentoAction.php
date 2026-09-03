<?php

namespace App\Actions\{SalaDeAgendamento}s;

use App\Models\SalaDeAgendamento;

class CreateSalaDeAgendamentoAction
{
    public function handle(array $data): SalaDeAgendamento
    {
        return SalaDeAgendamento::create($data);
    }
}