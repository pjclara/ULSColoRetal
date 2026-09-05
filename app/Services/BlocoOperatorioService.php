<?php

namespace App\Services;

use App\Models\BlocoOperatorio;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BlocoOperatorioService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return BlocoOperatorio::query()
            ->latest()
            ->paginate($perPage);
    }
}