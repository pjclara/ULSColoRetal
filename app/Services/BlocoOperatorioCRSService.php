<?php

namespace App\Services;

use App\Models\BlocoOperatorioCRS;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BlocoOperatorioCRSService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return BlocoOperatorioCRS::query()
            ->latest()
            ->paginate($perPage);
    }
}