<?php

namespace App\Services;

use App\Models\Internamento;
use App\Models\OrigemDoInternamento;
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
            ->withQueryString()->through(function ($internamento) {
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


    public function forUtente(int $utenteId, ?string $search = null)
    {
        return Internamento::query()
            ->where('utente_id', $utenteId)
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('cama', 'like', "%{$search}%")
                        ->orWhere(
                            'motivo_internamento',
                            'like',
                            "%{$search}%"
                        );
                });
            })
            ->orderBy('data_de_entrada', 'desc')
            ->paginate(15)
            ->withQueryString()
            ->withQueryString()->through(function ($internamento) {
                return [
                    'id' => $internamento->id,
                    'nome_paciente' => $internamento->utente?->nome ?? 'N/A',
                    'numero_processo' => $internamento->utente?->numero_processo ?? 'N/A',
                    'cama' => $internamento->cama ?? 'N/A',
                    'data_de_entrada' => $internamento->data_de_entrada ?? 'N/A',
                    'data_de_alta' => $internamento->data_de_alta ?? 'N/A',
                    'data_de_saida' => $internamento->data_de_saida ?? 'N/A',
                    'estado_da_alta_id' => $internamento->estadoDaAlta->nome ?? 'N/A',
                    'motivo_internamento' => $internamento->motivo_internamento ?? 'N/A',
                    'observacoes' => $internamento->observacoes ?? 'N/A',
                    'responsavel' => $internamento->responsavel->abrev ?? 'N/A',
                    'created_at' => $internamento->created_at?->toDateTimeString(),
                    'updated_at' => $internamento->updated_at?->toDateTimeString(),
                ];
            });
    }

    public function getOrigensInternamento()
    {
        return OrigemDoInternamento::query()
            ->orderBy('nome')
            ->get(['id', 'nome']);
    }

    public function getEstadosAlta()
    {
        return \App\Models\EstadoDaAlta::query()
            ->orderBy('nome')
            ->get(['id', 'nome']);
    }

    public function getResponsaveis()
    {
        return \App\Models\User::query()
            ->whereActivo(true)
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    public function getClavienDindo()
    {
        return \App\Models\ClavienDindo::query()
            ->orderBy('nome')
            ->get(['id', 'nome']);
    }

    public function getDestinos()
    {
        return \App\Models\Destino::query()
            ->orderBy('nome')
            ->get(['id', 'nome']);
    }

    public function getCasosSociais()
    {
        return \App\Models\CasoSocial::query()
            ->orderBy('nome')
            ->get(['id', 'nome']);
    }
}
