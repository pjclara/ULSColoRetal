<?php

namespace App\Services;

use App\Models\EstadoDaAlta;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EstadoDaAltaService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return EstadoDaAlta::query()
            ->latest()
            ->paginate($perPage);
    }
}