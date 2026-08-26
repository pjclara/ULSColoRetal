<?php

namespace App\Services;

use App\Models\Destino;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DestinoService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Destino::query()
            ->latest()
            ->paginate($perPage);
    }
}