<?php

namespace App\Services;

use App\Models\Internamento;
use App\Models\Utente;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class InternamentoService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Internamento::query()
            ->where('data_de_saida', null)
            ->latest()
            ->paginate($perPage)->through(function ($internamento) {
                return [
                    'id' => $internamento->id,
                    'nome_paciente' => $internamento->utente?->nome ?? 'N/A',
                    'numero_processo' => $internamento->utente?->numero_processo ?? 'N/A',
                    'cama' => $internamento->cama ?? 'N/A',
                    'data_internamento' => $internamento->data_de_entrada ?? 'N/A',
                    'data_alta' => $internamento->data_alta ?? 'N/A',
                    'motivo_internamento' => $internamento->motivo_internamento ?? 'N/A',
                    'observacoes' => $internamento->observacoes ?? 'N/A',
                    'responsavel' => $internamento->responsavel->abrev ?? 'N/A',
                    'created_at' => $internamento->created_at?->toDateTimeString(),
                    'updated_at' => $internamento->updated_at?->toDateTimeString(),
                ];
            });
    }

    public function search(array $filters)
    {
        return Utente::query()
            ->when(
                $filters['search'] ?? null,
                function ($query, $search) {
                    $search = trim($search);

                    $query->where(function ($query) use ($search) {
                        $query
                            ->where('nome', 'like', "%{$search}%")
                            ->orWhere(
                                'numero_processo',
                                'like',
                                "%{$search}%"
                            );
                    });
                }
            )
            ->orderBy('nome')
            ->paginate(15)
            ->withQueryString();
    }
}
