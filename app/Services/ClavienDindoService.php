<?php

namespace App\Services;

use App\Models\ClavienDindo;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ClavienDindoService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return ClavienDindo::query()
            ->latest()
            ->paginate($perPage);
    }
}