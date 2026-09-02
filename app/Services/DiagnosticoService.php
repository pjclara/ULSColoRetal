<?php

namespace App\Services;

use App\Models\Diagnostico;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DiagnosticoService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Diagnostico::query()
            ->latest()
            ->paginate($perPage);
    }

    public function getDiagnosticosAgrupados(): array
    {
        $diagnosticos = Diagnostico::with('grupoDiagnostico')
            ->orderBy('grupo_diagnostico_id')
            ->orderBy('nome')
            ->get();

        $diagnosticosAgrupados = [];

        foreach ($diagnosticos as $diagnostico) {
            $grupoNome = $diagnostico->grupoDiagnostico->nome ?? 'Sem Grupo';
            if (!isset($diagnosticosAgrupados[$grupoNome])) {
                $diagnosticosAgrupados[$grupoNome] = [];
            }
            $diagnosticosAgrupados[$grupoNome][] = $diagnostico;
        }

        return $diagnosticosAgrupados;
    }
}