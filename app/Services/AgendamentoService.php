<?php

namespace App\Services;

use App\Models\Agendamento;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AgendamentoService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Agendamento::query()
            ->latest()
            ->paginate($perPage);
    }
}