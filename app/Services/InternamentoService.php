<?php

namespace App\Services;

use App\Models\Internamento;
use App\Models\OrigemDoInternamento;
use App\Models\Utente;
use App\ViewModels\UtenteViewModel;
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
                    'nome_curto' => $internamento->utente?->nome_curto ?? 'N/A',
                    'numero_processo' => $internamento->utente?->numero_processo ?? 'N/A',
                    'cama' => $internamento->cama ?? 'N/A',
                    'data_de_entrada' => $internamento->data_de_entrada->format('Y-m-d') ?? 'N/A',
                    'origem_do_internamento_id' => $internamento->origem_do_internamento_id ?? 'N/A',
                    'data_alta' => $internamento->data_alta ?? 'N/A',
                    'motivo_internamento' => $internamento->motivo_internamento ?? 'N/A',
                    'observacoes' => $internamento->observacoes ?? 'N/A',
                    'responsavel_id' => $internamento->responsavel_id ?? 'N/A',
                    'responsavel' => $internamento->responsavel->abrev ?? 'N/A',
                    'localizacao_id' => $internamento->localizacao_id ?? 'N/A',
                    'diagnosticos' => $internamento->diagnosticos->map(function ($diagnostico) {
                        return [
                            'id' => $diagnostico->id,
                            'nome' => $diagnostico->nome,
                        ];
                    }),
                    'complicacaos' => $internamento->complicacaos->map(function ($complicacao) {
                        return [
                            'id' => $complicacao->id,
                            'nome' => $complicacao->nome,
                        ];
                    }),
                    'utente' => new UtenteViewModel($internamento->utente),
                ];
            });
    }

    public function search(array $filters)
    {
        return Utente::query()
            ->with('centroDeReferencia')
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
            ->withQueryString()->through(function ($internamento) {
                return [
                    'id' => $internamento->id,
                    'nome_curto' => $internamento->utente?->nome_curto ?? 'N/A',
                    'nome_paciente' => $internamento->utente?->nome ?? 'N/A',
                    'numero_processo' => $internamento->utente?->numero_processo ?? 'N/A',
                    'cama' => $internamento->cama ?? 'N/A',
                    'data_de_entrada' => $internamento->data_de_entrada ?? 'N/A',
                    'data_de_alta' => $internamento->data_de_alta ?? 'N/A',
                    'data_de_saida' => $internamento->data_de_saida ?? 'N/A',
                    'estadoDaAlta' => $internamento->estado_da_alta_id ?? 'N/A',
                    'motivo_internamento' => $internamento->motivo_internamento ?? 'N/A',
                    'observacoes' => $internamento->observacoes ?? 'N/A',
                    'responsavel' => $internamento->responsavel->abrev ?? 'N/A',
                    'centro_de_referencia' => $internamento->utente->centroDeReferencia?->id ?? 'N/A',
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

    public function create(array $data): Internamento
    {
        return Internamento::create($data);
    }

    public function update(Internamento $internamento, array $data): Internamento
    {
        $internamento->update($data);

        return $internamento;
    }

    public function getLocalizacoes()
    {
        return \App\Models\Localizacao::query()
            ->orderBy('nome')
            ->get(['id', 'nome']);
    }

    public function getOrigensDaReferenciacao()
    {
        return \App\Models\OrigemDaReferenciacao::query()
            ->orderBy('nome')
            ->get(['id', 'nome']);
    }

    public function addDiagnostico(Internamento $internamento, int $diagnosticoId)
    {
        $internamento->diagnosticos()->attach($diagnosticoId);
    }

    // remove diagnostico from internamento
    public function removeDiagnostico(Internamento $internamento, int $diagnosticoId)
    {
         $internamento->diagnosticos()->detach($diagnosticoId);
    }
}
