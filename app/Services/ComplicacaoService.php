<?php

namespace App\Services;

use App\Models\Complicacao;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ComplicacaoService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Complicacao::query()
            ->latest()
            ->paginate($perPage);
    }

    public function getComplicacoesOptions()
    {
        return Complicacao::query()
            ->where('nome', '!=', 'Sem complicações')
            ->orderBy('nome')
            ->get(['id', 'nome']);
    }

    public function getComplicacoesAgrupadas(): array
    {
        $complicacoes = Complicacao::with('grupoComplicacao')
            ->orderBy('grupo_complicacao_id')
            ->orderBy('nome')
            ->get();

        $complicacoesAgrupadas = [];

        foreach ($complicacoes as $complicacao) {
            $grupoNome = $complicacao->grupoComplicacao->nome ?? 'Sem Grupo';
            if (!isset($complicacoesAgrupadas[$grupoNome])) {
                $complicacoesAgrupadas[$grupoNome] = [];
            }
            $complicacoesAgrupadas[$grupoNome][] = $complicacao;
        }

        return $complicacoesAgrupadas;
    }
}