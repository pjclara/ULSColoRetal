<?php

namespace App\Services;

use App\Models\OrigemDaReferenciacao;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OrigemDaReferenciacaoService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return OrigemDaReferenciacao::query()
            ->latest()
            ->paginate($perPage);
    }
}