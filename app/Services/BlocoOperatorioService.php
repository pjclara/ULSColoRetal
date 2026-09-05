<?php

namespace App\Services;

use App\Models\BlocoOperatorio;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BlocoOperatorioService
{
    public function __construct(
        private InternamentoService $internamentoService
    ) {}

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

        // ter um bloco operatório torna o doente "operado" — o estado da alta passa a Pendente.
        $this->internamentoService->atualizarEstadoDaAlta($blocoOperatorio->internamento);

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
        $internamento = $blocoOperatorio->internamento;

        $deleted = $blocoOperatorio->delete();

        // se este era o último bloco operatório do internamento, deixa de estar "operado".
        $this->internamentoService->atualizarEstadoDaAlta($internamento);

        return $deleted;
    }
}
