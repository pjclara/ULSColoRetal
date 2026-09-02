<?php

namespace App\Services;

use App\Models\ListaDeEspera;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListaDeEsperaService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return ListaDeEspera::query()
            ->latest()
            ->paginate($perPage)->through(fn($listaDeEspera) => [
                'id' => $listaDeEspera->id,
                'nome' => $listaDeEspera->utente->nome_curto,
                'utente_id' => $listaDeEspera->utente->id,
                'numero_processo' => $listaDeEspera->utente->numero_processo,
                'diagnosticos' => $listaDeEspera->diagnosticos->map(fn($diagnostico) => [
                    'id' => $diagnostico->id,
                    'nome' => $diagnostico->nome,
                ]),
                'responsavel_id' => $listaDeEspera->responsavel->id ?? null,
                'responsavel_nome' => $listaDeEspera->responsavel->abrev ?? null,
                'agendamentos' => $listaDeEspera->agendamentos->map(fn($agendamento) => [
                    'id' => $agendamento->id,
                    'start' => $agendamento->start,
                    'end' => $agendamento->end,
                ]),
            ]);
    }
}