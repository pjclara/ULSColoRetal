<?php

namespace App\Services;

use App\Models\Agendamento;
use App\Models\BlocoOperatorio;
use App\Models\Internamento;
use App\Models\EstadoDeAgendamento;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

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
     * Marca como "Operado" os agendamentos dos utentes listados num Excel com as colunas
     * DTA_INTERVENCAO e NUM_PROCESSO. Por cada linha é escolhido o agendamento do utente com
     * `start` no mesmo dia da intervenção; se já estiver "Operado" não é alterado.
     *
     * @return array{atualizados: int, ja_operados: int, sem_agendamento: int, linhas_invalidas: int}
     */
    public function importarOperados(string $path): array
    {
        $estadoOperado = EstadoDeAgendamento::all()
            ->first(fn (EstadoDeAgendamento $estado) => strtolower(trim($estado->nome)) === 'operado');

        if (!$estadoOperado) {
            throw new \RuntimeException('O estado de agendamento "Operado" não existe.');
        }

        $linhas = IOFactory::load($path)->getActiveSheet()->toArray(null, true, false, false);
        $cabecalho = array_map(fn ($c) => strtoupper(trim((string) $c)), array_shift($linhas) ?? []);
        $colData = array_search('DTA_INTERVENCAO', $cabecalho, true);
        $colProcesso = array_search('NUM_PROCESSO', $cabecalho, true);

        if ($colData === false || $colProcesso === false) {
            throw new \RuntimeException('O ficheiro tem de ter as colunas DTA_INTERVENCAO e NUM_PROCESSO.');
        }

        $resultado = ['atualizados' => 0, 'ja_operados' => 0, 'sem_agendamento' => 0, 'linhas_invalidas' => 0];

        foreach ($linhas as $linha) {
            $processo = trim((string) ($linha[$colProcesso] ?? ''));
            $data = $this->parseDataExcel($linha[$colData] ?? null);

            if ($processo === '' && $data === null) {
                continue; // linha vazia
            }

            if ($processo === '' || $data === null) {
                $resultado['linhas_invalidas']++;
                continue;
            }

            $agendamento = Agendamento::query()
                ->whereHas('listaDeEspera.utente', fn ($q) => $q->where('numero_processo', $processo))
                ->whereDate('start', $data->toDateString())
                ->first();

            if (!$agendamento) {
                $resultado['sem_agendamento']++;
            } elseif ($agendamento->estado_de_agendamento_id === $estadoOperado->id) {
                $resultado['ja_operados']++;
            } else {
                $this->update($agendamento, ['estado_de_agendamento_id' => $estadoOperado->id]);
                $resultado['atualizados']++;
            }
        }

        return $resultado;
    }

    private function parseDataExcel(mixed $valor): ?Carbon
    {
        if ($valor === null || $valor === '') {
            return null;
        }

        try {
            return is_numeric($valor)
                ? Carbon::instance(ExcelDate::excelToDateTimeObject($valor))
                : Carbon::parse((string) $valor);
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Agendamentos do utente do internamento cujo `start` cai no dia indicado.
     */
    private function agendamentosDoDia(?int $internamentoId, mixed $dia): Collection
    {
        $utenteId = $internamentoId ? Internamento::find($internamentoId)?->utente_id : null;

        if (!$utenteId || !$dia) {
            return new Collection();
        }

        return Agendamento::query()
            ->whereHas('listaDeEspera', fn ($q) => $q->where('utente_id', $utenteId))
            ->whereDate('start', Carbon::parse($dia)->toDateString())
            ->get();
    }

    /**
     * Um bloco operatório no dia do agendamento do mesmo utente marca-o "Operado".
     */
    public function marcarOperadosPorBloco(BlocoOperatorio $bloco): int
    {
        $operado = EstadoDeAgendamento::idPorNome('operado');

        if (!$operado) {
            return 0;
        }

        $pendentes = $this->agendamentosDoDia($bloco->internamento_id, $bloco->data_de_inicio)
            ->where('estado_de_agendamento_id', '!=', $operado);

        foreach ($pendentes as $agendamento) {
            $this->update($agendamento, ['estado_de_agendamento_id' => $operado]);
        }

        return $pendentes->count();
    }

    /**
     * Sem nenhum bloco operatório (ativo) do utente nesse dia, os agendamentos desse dia
     * "Operado" voltam a "Agendado".
     */
    public function reverterOperadosPorBloco(?int $internamentoId, mixed $dia): void
    {
        $operado = EstadoDeAgendamento::idPorNome('operado');
        $agendado = EstadoDeAgendamento::idPorNome('agendado');
        $utenteId = $internamentoId ? Internamento::find($internamentoId)?->utente_id : null;

        if (!$operado || !$agendado || !$utenteId || !$dia) {
            return;
        }

        $aindaOperado = BlocoOperatorio::query()
            ->whereDate('data_de_inicio', Carbon::parse($dia)->toDateString())
            ->whereHas('internamento', fn ($q) => $q->where('utente_id', $utenteId))
            ->exists();

        if ($aindaOperado) {
            return;
        }

        foreach ($this->agendamentosDoDia($internamentoId, $dia)->where('estado_de_agendamento_id', $operado) as $agendamento) {
            $this->update($agendamento, ['estado_de_agendamento_id' => $agendado]);
        }
    }

    /**
     * Marca "Operado" todos os agendamentos com bloco operatório do mesmo utente no mesmo dia.
     * Usado para regularizar os dados existentes.
     */
    public function sincronizarOperadosComBlocos(): int
    {
        $total = 0;

        foreach (BlocoOperatorio::query()->with('internamento')->get() as $bloco) {
            $total += $this->marcarOperadosPorBloco($bloco);
        }

        return $total;
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

        // Só volta a "Pendente" se não restar outro agendamento da mesma lista de espera.
        if ($listaDeEspera && !$listaDeEspera->agendamentos()->exists()) {
            $listaDeEspera->update(['estado_lista_espera' => self::ESTADO_LISTA_ESPERA_PENDENTE]);
        }

        return true;
    }
}
