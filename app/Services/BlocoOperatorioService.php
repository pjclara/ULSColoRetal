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

    public function create(array $data): BlocoOperatorio
    {
        $intervencaoIds = $data['intervencao_ids'] ?? [];
        unset($data['intervencao_ids']);

        $blocoOperatorio = BlocoOperatorio::create($data);
        $blocoOperatorio->intervencoesCirurgicas()->sync($intervencaoIds);

        return $blocoOperatorio->load(['tipoDeCirurgia', 'intervencoesCirurgicas']);
    }

    public function update(BlocoOperatorio $blocoOperatorio, array $data): BlocoOperatorio
    {
        if (array_key_exists('intervencao_ids', $data)) {
            $blocoOperatorio->intervencoesCirurgicas()->sync($data['intervencao_ids'] ?? []);
            unset($data['intervencao_ids']);
        }

        $blocoOperatorio->update($data);

        return $blocoOperatorio->load(['tipoDeCirurgia', 'intervencoesCirurgicas']);
    }

    public function delete(BlocoOperatorio $blocoOperatorio): bool
    {
        return $blocoOperatorio->delete();
    }
}