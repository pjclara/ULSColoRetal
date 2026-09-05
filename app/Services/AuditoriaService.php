<?php

namespace App\Services;

use App\Models\Complicacao;
use App\Models\Internamento;
use Illuminate\Support\Collection;

/**
 * Estatísticas de auditoria de internamentos/cirurgias, agregadas por ano, semestre ou trimestre.
 *
 * Definições usadas (não reproduzem necessariamente relatórios antigos — foram escolhidas para
 * serem simples, coerentes entre si e auditáveis a partir do próprio ecrã):
 *
 * - O período de um internamento é sempre determinado pela sua data de entrada.
 * - "Doente operado" = internamento com pelo menos um bloco operatório.
 * - As colunas Programada/Urgência/Outras contam CIRURGIAS (blocos operatórios), não doentes —
 *   um doente pode ter mais do que uma cirurgia no mesmo período.
 * - "Programada" agrega TipoDeCirurgia: Programada e Adicional.
 * - "Urgência" agrega TipoDeCirurgia: Urgência, Urgente_Oclusão, Urgente_Perfuração, Urgente_Hemorragia.
 * - "Outras" agrega Ambulatório e Outro (mostrada para que os totais sejam sempre auditáveis).
 * - As percentagens de Complicações (ligeiras/graves) / Deiscências / Óbitos entre operados são
 *   sobre o total de cirurgias do período (Programada + Urgência + Outras).
 * - Complicações são classificadas pela gravidade Clavien-Dindo do internamento: "ligeiras" =
 *   Grau 1-2, "graves" = Grau 3A a 5. Internamentos sem grau atribuído (ou "A aguardar...") não
 *   contam em nenhuma das duas.
 * - "Deiscências" conta internamentos com complicação de deiscência (anastomose ou ferida operatória).
 * - Óbito = internamento com destino "Falecido".
 */
class AuditoriaService
{
    private const TIPO_PROGRAMADA = [1, 2];
    private const TIPO_URGENCIA = [4, 5, 6, 7];
    private const TIPO_OUTRAS = [3, 8];

    // ClavienDindo: 1=Grau 1, 2=Grau 2, 3=Grau 3A, 4=Grau 3B, 5=Grau 4A, 6=Grau 4B, 7=Grau 5, 8=A aguardar...
    private const CLAVIEN_LIGEIRA = [1, 2];
    private const CLAVIEN_GRAVE = [3, 4, 5, 6, 7];

    private const DESTINO_FALECIDO_NOME = 'Falecido';

    public function relatorio(string $periodo = 'ano'): array
    {
        $periodo = in_array($periodo, ['ano', 'semestre', 'trimestre'], true) ? $periodo : 'ano';

        $deiscenciaIds = Complicacao::query()
            ->whereId([28]) // Deiscência de anastomose ou ferida operatória
            ->pluck('id');

        $internamentos = Internamento::query()
            ->whereNotNull('data_de_entrada')
            ->with(['blocoOperatorios', 'complicacaos', 'destino'])
            ->get();

        $grupos = $internamentos->groupBy(fn (Internamento $internamento) => $this->chaveDoPeriodo($internamento->data_de_entrada, $periodo));

        return $grupos
            ->map(fn (Collection $grupo, string $chave) => $this->linha($chave, $grupo, $deiscenciaIds))
            ->sortKeys()
            ->values()
            ->all();
    }

    private function chaveDoPeriodo(\Carbon\Carbon $data, string $periodo): string
    {
        return match ($periodo) {
            'semestre' => $data->year . ' S' . (intdiv($data->month - 1, 6) + 1),
            'trimestre' => $data->year . ' T' . $data->quarter,
            default => (string) $data->year,
        };
    }

    private function linha(string $periodo, Collection $internamentos, Collection $deiscenciaIds): array
    {
        $operados = $internamentos->filter(fn (Internamento $i) => $i->blocoOperatorios->isNotEmpty());

        $blocos = $internamentos->flatMap(fn (Internamento $i) => $i->blocoOperatorios);

        $porTipo = fn (array $tipoIds) => $blocos->filter(fn ($b) => in_array($b->tipo_de_cirurgia_id, $tipoIds, true));

        $programada = $porTipo(self::TIPO_PROGRAMADA);
        $urgencia = $porTipo(self::TIPO_URGENCIA);
        $outras = $porTipo(self::TIPO_OUTRAS);

        $totalCirurgias = $blocos->count();

        $obitos = $internamentos->filter(fn (Internamento $i) => $i->destino?->nome === self::DESTINO_FALECIDO_NOME);
        $obitosOperados = $operados->filter(fn (Internamento $i) => $i->destino?->nome === self::DESTINO_FALECIDO_NOME);

        $complicacoesLigeiras = $internamentos->filter(fn (Internamento $i) => in_array($i->clavien_dindo_id, self::CLAVIEN_LIGEIRA, true));
        $complicacoesGraves = $internamentos->filter(fn (Internamento $i) => in_array($i->clavien_dindo_id, self::CLAVIEN_GRAVE, true));

        $deiscencias = $internamentos->filter(fn (Internamento $i) => $i->complicacaos
            ->pluck('id')
            ->intersect($deiscenciaIds)
            ->isNotEmpty());

        return [
            'periodo' => $periodo,
            'internamentos' => $internamentos->count(),
            'mediana_ti' => $this->mediana($internamentos->pluck('dias_internamento')),
            'obitos' => $obitos->count(),
            'obitos_pct' => $this->percentagem($obitos->count(), $internamentos->count()),
            'doentes_operados' => $operados->count(),
            'mediana_ti_operados' => $this->mediana($operados->pluck('dias_internamento')),
            'programada' => $programada->count(),
            'mediana_programada' => $this->mediana($this->internamentosDosBlocos($internamentos, self::TIPO_PROGRAMADA)),
            'urgencia' => $urgencia->count(),
            'mediana_urgencia' => $this->mediana($this->internamentosDosBlocos($internamentos, self::TIPO_URGENCIA)),
            'outras' => $outras->count(),
            'total_cirurgias' => $totalCirurgias,
            'complicacoes_ligeiras' => $complicacoesLigeiras->count(),
            'complicacoes_ligeiras_pct' => $this->percentagem($complicacoesLigeiras->count(), $totalCirurgias),
            'complicacoes_graves' => $complicacoesGraves->count(),
            'complicacoes_graves_pct' => $this->percentagem($complicacoesGraves->count(), $totalCirurgias),
            'deiscencias' => $deiscencias->count(),
            'deiscencias_pct' => $this->percentagem($deiscencias->count(), $totalCirurgias),
            'obitos_entre_operados' => $obitosOperados->count(),
            'obitos_entre_operados_pct' => $this->percentagem($obitosOperados->count(), $totalCirurgias),
        ];
    }

    /** Dias de internamento dos internamentos que têm pelo menos um bloco de um dos tipos indicados. */
    private function internamentosDosBlocos(Collection $internamentos, array $tipoIds): Collection
    {
        return $internamentos
            ->filter(fn (Internamento $i) => $i->blocoOperatorios->contains(fn ($b) => in_array($b->tipo_de_cirurgia_id, $tipoIds, true)))
            ->pluck('dias_internamento');
    }

    private function mediana(Collection $valores): ?float
    {
        $valores = $valores->filter(fn ($v) => $v !== null)->sort()->values();
        $total = $valores->count();

        if ($total === 0) {
            return null;
        }

        $meio = intdiv($total, 2);

        if ($total % 2 === 0) {
            return ($valores[$meio - 1] + $valores[$meio]) / 2;
        }

        return (float) $valores[$meio];
    }

    private function percentagem(int $parte, int $total): ?float
    {
        if ($total === 0) {
            return null;
        }

        return round(($parte / $total) * 100, 2);
    }
}
