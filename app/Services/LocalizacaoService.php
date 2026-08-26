<?php

namespace App\Services;

use App\Models\Localizacao;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LocalizacaoService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Localizacao::query()
            ->latest()
            ->paginate($perPage);
    }
}