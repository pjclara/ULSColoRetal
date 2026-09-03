<?php

namespace App\Services;

use App\Models\EstadoDeAgendamento;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EstadoDeAgendamentoService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return EstadoDeAgendamento::query()
            ->latest()
            ->paginate($perPage);
    }
}