<?php

namespace App\Services;

use App\Models\CasoSocial;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CasoSocialService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return CasoSocial::query()
            ->latest()
            ->paginate($perPage);
    }
}