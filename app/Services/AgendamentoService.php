<?php

namespace App\Services;

use App\Models\Agendamento;
use App\Models\EstadoDeAgendamento;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AgendamentoService
{
    /** Valor de `lista_de_esperas.estado_lista_espera` que representa "Concluída" (ver ListaDeEsperaController). */
    private const ESTADO_LISTA_ESPERA_CONCLUIDA = '3';

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Agendamento::query()
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data): Agendamento
    {
        $agendamento = Agendamento::create($data);

        $this->syncEstadoListaDeEspera($agendamento);

        return $agendamento;
    }

    public function update(Agendamento $agendamento, array $data): Agendamento
    {
        $agendamento->update($data);

        $this->syncEstadoListaDeEspera($agendamento);

        return $agendamento;
    }

    /**
     * Quando o agendamento fica "Operado", marca a lista de espera associada como "Concluída".
     * O estado é resolvido pelo nome (não pelo id) porque `estado_de_agendamentos` é uma tabela
     * gerida livremente pelo utilizador, sem ids fixos garantidos entre ambientes.
     */
    private function syncEstadoListaDeEspera(Agendamento $agendamento): void
    {
        if (!$agendamento->estado_de_agendamento_id) {
            return;
        }

        $estadoOperadoId = EstadoDeAgendamento::whereRaw('LOWER(nome) = ?', ['operado'])->value('id');

        if ($estadoOperadoId && (int) $agendamento->estado_de_agendamento_id === (int) $estadoOperadoId) {
            $agendamento->listaDeEspera?->update([
                'estado_lista_espera' => self::ESTADO_LISTA_ESPERA_CONCLUIDA,
            ]);
        }
    }

    public function delete(Agendamento $agendamento): bool
    {
        return $agendamento->delete();
    }
}melhorar o aspecto estetico de modo mais moderno e