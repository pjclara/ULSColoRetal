<?php

namespace App\Services;

use App\Models\SalaDeAgendamento;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SalaDeAgendamentoService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return SalaDeAgendamento::query()
            ->latest()
            ->paginate($perPage);
    }
}