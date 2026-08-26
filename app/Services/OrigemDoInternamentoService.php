<?php

namespace App\Services;

use App\Models\OrigemDoInternamento;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OrigemDoInternamentoService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return OrigemDoInternamento::query()
            ->latest()
            ->paginate($perPage);
    }
}