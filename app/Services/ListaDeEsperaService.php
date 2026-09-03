<?php

namespace App\Services;

use App\Models\ListaDeEspera;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListaDeEsperaService
{
    public function create(array $data): ListaDeEspera
    {
        $data['cancelar_lista_espera'] = ($data['cancelar_lista_espera'] ?? false) ? '1' : null;

        return ListaDeEspera::create($data);
    }

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

    public function find(int $id): ?ListaDeEspera
    {
        return ListaDeEspera::find($id);
    }



    public function update(int $id, array $data): ?ListaDeEspera
    {
        $listaDeEspera = $this->find($id);
        if (!$listaDeEspera) {
            return null;
        }

        $data['cancelar_lista_espera'] = ($data['cancelar_lista_espera'] ?? false) ? '1' : null;
        $listaDeEspera->update($data);

        return $listaDeEspera;
    }

    public function delete(int $id): bool
    {
        $listaDeEspera = $this->find($id);
        if (!$listaDeEspera) {
            return false;
        }

        return $listaDeEspera->delete();
    }


}