<?php

namespace App\Services;

use App\Models\Agendamento;
use App\Models\EstadoDeAgendamento;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class AgendamentoService
{
    /** Valor de `lista_de_esperas.estado_lista_espera` que representa "Pendente" (ver ListaDeEsperaController). */
    private const ESTADO_LISTA_ESPERA_PENDENTE = '1';

    /**
     * Nome do estado do agendamento (em minúsculas) => valor correspondente em
     * `lista_de_esperas.estado_lista_espera` (ver ListaDeEsperaController).
     */
    private const ESTADO_AGENDAMENTO_PARA_LISTA = [
        'agendado' => '2',
        'operado' => '3',
        'cancelado' => '4',
    ];

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
     * O estado do agendamento (agendado/operado/cancelado) reflete-se sempre na lista de espera
     * associada, ficando os dois iguais. O estado é resolvido pelo nome (não pelo id) porque
     * `estado_de_agendamentos` é uma tabela gerida livremente pelo utilizador, sem ids fixos
     * garantidos entre ambientes.
     */
    private function syncEstadoListaDeEspera(Agendamento $agendamento): void
    {
        if (!$agendamento->estado_de_agendamento_id) {
            return;
        }

        $nomeEstado = strtolower(trim(
            EstadoDeAgendamento::find($agendamento->estado_de_agendamento_id)?->nome ?? ''
        ));
        $estadoLista = self::ESTADO_AGENDAMENTO_PARA_LISTA[$nomeEstado] ?? null;

        if ($estadoLista) {
            $agendamento->listaDeEspera?->update(['estado_lista_espera' => $estadoLista]);
        }
    }

    /**
     * Ao eliminar o agendamento, a lista de espera associada volta a "Pendente".
     */
    public function delete(Agendamento $agendamento): bool
    {
        $listaDeEspera = $agendamento->listaDeEspera;

        if (!$agendamento->delete()) {
            return false;
        }

        $listaDeEspera?->update(['estado_lista_espera' => self::ESTADO_LISTA_ESPERA_PENDENTE]);

        return true;
    }
}
