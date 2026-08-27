<?php

namespace App\Services;

use App\Models\CentroDeReferencia;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CentroDeReferenciaService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return CentroDeReferencia::query()
            ->latest()
            ->paginate($perPage);
    }
}