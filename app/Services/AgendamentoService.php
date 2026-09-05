<?php

namespace App\Services;

use App\Models\Agendamento;
use App\Models\EstadoDeAgendamento;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

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

    /**
     * Agendamentos cujo intervalo [start, end] intersecta a janela pedida (usado pelo calendário).
     */
    public function betweenDates(Carbon $start, Carbon $end): Collection
    {
        return Agendamento::query()
            ->with([
                'listaDeEspera.utente',
                'responsavel',
                'tipoDeAgendamento',
                'localDeAgendamento',
                'salaDeAgendamento',
                'periodoDeAgendamento',
                'estadoDeAgendamento',
            ])
            ->where('start', '<=', $end)
            ->where('end', '>=', $start)
            ->orderBy('start')
            ->get();
    }

    public function create(array $data): Agendamento
    {
        $agendamento = Agendamento::create($this->withMatchingEnd($data));

        $this->syncEstadoListaDeEspera($agendamento);

        return $agendamento;
    }

    public function update(Agendamento $agendamento, array $data): Agendamento
    {
        $agendamento->update($this->withMatchingEnd($data));

        $this->syncEstadoListaDeEspera($agendamento);

        return $agendamento;
    }

    /**
     * Um agendamento é um ponto no tempo, não um intervalo: `end` acompanha sempre `start`,
     * independentemente do que o cliente envie (o formulário e o arrastar no calendário já
     * não pedem/enviam um fim distinto).
     */
    private function withMatchingEnd(array $data): array
    {
        if (array_key_exists('start', $data)) {
            $data['end'] = $data['start'];
        }

        return $data;
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
}
