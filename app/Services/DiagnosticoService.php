<?php

namespace App\Services;

use App\Models\Diagnostico;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DiagnosticoService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Diagnostico::query()
            ->latest()
            ->paginate($perPage);
    }
}