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

    public function create(array $data): BlocoOperatorioCRS
    {
        return BlocoOperatorioCRS::create($data);
    }

    public function update(BlocoOperatorioCRS $blocoOperatorioCRS, array $data): BlocoOperatorioCRS
    {
        $blocoOperatorioCRS->update($data);

        return $blocoOperatorioCRS;
    }

    public function delete(BlocoOperatorioCRS $blocoOperatorioCRS): bool
    {
        return $blocoOperatorioCRS->delete();
    }
}