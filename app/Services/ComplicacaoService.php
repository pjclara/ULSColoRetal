<?php

namespace App\Services;

use App\Models\Complicacao;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ComplicacaoService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Complicacao::query()
            ->latest()
            ->paginate($perPage);
    }
}