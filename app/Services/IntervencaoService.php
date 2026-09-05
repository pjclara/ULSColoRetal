<?php

namespace App\Services;

use App\Models\Intervencao;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class IntervencaoService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Intervencao::query()
            ->latest()
            ->paginate($perPage);
    }
}