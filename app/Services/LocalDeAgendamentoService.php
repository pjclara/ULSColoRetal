<?php

namespace App\Services;

use App\Models\LocalDeAgendamento;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LocalDeAgendamentoService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return LocalDeAgendamento::query()
            ->latest()
            ->paginate($perPage);
    }
}