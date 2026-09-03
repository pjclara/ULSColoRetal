<?php

namespace App\Actions\{TipoDeAgendamento}s;

use App\Models\TipoDeAgendamento;

class CreateTipoDeAgendamentoAction
{
    public function handle(array $data): TipoDeAgendamento
    {
        return TipoDeAgendamento::create($data);
    }
}