<?php

namespace App\Services;

use App\Models\TipoDeAgendamento;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TipoDeAgendamentoService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return TipoDeAgendamento::query()
            ->latest()
            ->paginate($perPage);
    }
}