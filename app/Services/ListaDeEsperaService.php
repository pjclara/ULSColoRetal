<?php

namespace App\Services;

use App\Models\ListaDeEspera;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListaDeEsperaService
{
    public function create(array $data): ListaDeEspera
    {
        $diagnosticoIds = $data['diagnostico_ids'] ?? [];
        unset($data['diagnostico_ids']);

        $data['cancelar_lista_espera'] = ($data['cancelar_lista_espera'] ?? false) ? '1' : null;

        $listaDeEspera = ListaDeEspera::create($data);
        $listaDeEspera->diagnosticos()->sync($diagnosticoIds);

        return $listaDeEspera;
    }

    public function paginate(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        return ListaDeEspera::with(['utente', 'diagnosticos', 'responsavel', 'agendamentos'])
            ->when($search, fn($query) => $query->whereHas('utente', fn($utenteQuery) => $utenteQuery
                ->where('nome', 'like', "%{$search}%")
                ->orWhere('numero_processo', 'like', "%{$search}%")))
            ->latest()
            ->paginate($perPage)
            ->withQueryString()
            ->through(fn($listaDeEspera) => [
                'id' => $listaDeEspera->id,
                'data_de_lista' => $listaDeEspera->data_de_lista->format('Y-m-d'),
                'estado_lista_espera' => $listaDeEspera->estado_lista_espera,
                'cancelar_lista_espera' => $listaDeEspera->cancelar_lista_espera,
                'comentarios' => $listaDeEspera->comentarios,
                'responsavel_id' => $listaDeEspera->responsavel_id,
                'nome' => $listaDeEspera->utente->nome_curto,
                'utente_id' => $listaDeEspera->utente->id,
                'numero_processo' => $listaDeEspera->utente->numero_processo,
                'diagnosticos' => $listaDeEspera->diagnosticos->map(fn($diagnostico) => [
                    'id' => $diagnostico->id,
                    'nome' => $diagnostico->nome,
                ]),
                'responsavel' => $listaDeEspera->responsavel ? [
                    'id' => $listaDeEspera->responsavel->id,
                    'name' => $listaDeEspera->responsavel->name,
                    'abrev' => $listaDeEspera->responsavel->abrev,
                ] : null,
                'agendamentos' => $listaDeEspera->agendamentos->map(fn($agendamento) => [
                    'id' => $agendamento->id,
                    'lista_de_espera_id' => $agendamento->lista_de_espera_id,
                    'responsavel_id' => $agendamento->responsavel_id,
                    'tipo_de_agendamento_id' => $agendamento->tipo_de_agendamento_id,
                    'local_de_agendamento_id' => $agendamento->local_de_agendamento_id,
                    'sala_de_agendamento_id' => $agendamento->sala_de_agendamento_id,
                    'periodo_de_agendamento_id' => $agendamento->periodo_de_agendamento_id,
                    'estado_de_agendamento_id' => $agendamento->estado_de_agendamento,
                    'start' => $agendamento->start->format('Y-m-d H:i'),
                    'end' => $agendamento->end->format('Y-m-d H:i'),
                ]),
            ]);
    }

    public function find(int $id): ?ListaDeEspera
    {
        return ListaDeEspera::find($id);
    }

    public function update(ListaDeEspera $listaDeEspera, array $data): ?ListaDeEspera
    {
        $data['cancelar_lista_espera'] = ($data['cancelar_lista_espera'] ?? false) ? '1' : null;
        $listaDeEspera->update($data);

        if (isset($data['diagnostico_ids'])) {
            $diagnosticoIds = $data['diagnostico_ids'];
            $listaDeEspera->diagnosticos()->sync($diagnosticoIds);
        }

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
