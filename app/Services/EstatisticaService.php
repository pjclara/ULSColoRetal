<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Indicadores anuais do serviço (cirurgias, internamentos, cirurgia colorretal),
 * calculados a partir da BD no formato do relatório em PDF.
 *
 * Definições (ajustar aqui se o critério clínico for outro):
 *  - ano das cirurgias = ano de `data_de_inicio` do bloco operatório;
 *  - ano dos internamentos = ano de `data_de_entrada`;
 *  - tempo de internamento = dias entre entrada e saída (só internamentos com saída);
 *  - morbilidade = Clavien-Dindo >= 3A; mortalidade = destino "Falecido".
 */
class EstatisticaService
{
    private const PRIMEIRO_ANO = 2022;

    private const DESTINO_FALECIDO = 'Falecido';

    private const COMPLICACAO_DEISCENCIA = 'Deiscência da anastomose';

    /** Graus Clavien-Dindo considerados morbilidade (3A, 3B, 4A, 4B, 5). */
    private const CLAVIEN_DINDO_MORBILIDADE = ['Grau 3 A', 'Grau 3 B', 'Grau 4 A', 'Grau 4 B', 'Grau 5'];

    /** Intervenções que definem a cirurgia colorretal, pela ordem do relatório. */
    private const INTERVENCOES_COLORRETAIS = [
        'Ressecção ileo-cecal',
        'Ressecção do polo cecal',
        'Hemicolectomia direita',
        'Colectomia segmentar do transverso',
        'Hemicolectomia esquerda',
        'Sigmoidectomia',
        'Resseção reto-sigmóidea',
        'Ressecção anterior do recto',
        'Amputação abdomino-perineal',
        'Colectomia subtotal',
        'Colectomia total',
        'Protocolectomia total',
        'Operação de hartman',
    ];

    private const ABORDAGENS = ['Laparoscópica', 'Laparotómica', 'Conversão'];

    public function build(): array
    {
        $anos = range(self::PRIMEIRO_ANO, max((int) date('Y'), self::PRIMEIRO_ANO));

        $internamentos = $this->internamentos($anos);
        $blocos = $this->blocos($anos, $internamentos);
        $colorretais = $blocos->filter(fn($b) => $b->colorretal);

        return [
            'anos' => $anos,
            'tabelas' => [
                $this->tabelaCirurgias($anos, $blocos),
                $this->tabelaInternamentos($anos, $internamentos, $blocos),
                $this->tabelaColorretal($anos, $colorretais),
                $this->tabelaIntervencoes($anos),
                $this->tabelaAbordagem($anos, $colorretais),
            ],
        ];
    }

    /** Internamentos do período, com o ano de entrada e o tempo de internamento. */
    private function internamentos(array $anos): Collection
    {
        $falecido = DB::table('destinos')->where('nome', self::DESTINO_FALECIDO)->value('id');
        $graves = DB::table('clavien_dindos')->whereIn('nome', self::CLAVIEN_DINDO_MORBILIDADE)->pluck('id')->all();
        $deiscencia = DB::table('complicacaos')->where('nome', self::COMPLICACAO_DEISCENCIA)->value('id');

        $comDeiscencia = DB::table('complicacao_internamento')
            ->where('complicacao_id', $deiscencia)
            ->pluck('internamento_id')
            ->flip();

        return DB::table('internamentos')
            ->whereNull('deleted_at')
            ->whereBetween(DB::raw('year(data_de_entrada)'), [min($anos), max($anos)])
            ->get(['id', 'data_de_entrada', 'data_de_saida', 'destino_id', 'clavien_dindo_id'])
            ->map(fn($i) => (object) [
                'id' => $i->id,
                'ano' => (int) substr($i->data_de_entrada, 0, 4),
                'dias' => $i->data_de_saida
                    ? (int) round((strtotime($i->data_de_saida) - strtotime($i->data_de_entrada)) / 86400)
                    : null,
                'faleceu' => $i->destino_id === $falecido,
                'morbilidade' => in_array($i->clavien_dindo_id, $graves, true),
                'deiscencia' => $comDeiscencia->has($i->id),
            ])
            ->keyBy('id');
    }

    /** Blocos operatórios do período, classificados por tipo e marcados se são colorretais. */
    private function blocos(array $anos, Collection $internamentos): Collection
    {
        $tipos = DB::table('tipo_de_cirurgias')->pluck('nome', 'id')->map(fn($nome) => match (true) {
            str_starts_with($nome, 'Urg') => 'urgencia',
            $nome === 'Ambulatório' => 'ambulatorio',
            default => 'programada',
        });
        $abordagens = DB::table('tipo_de_abordagems')->pluck('nome', 'id');

        $colorretais = DB::table('bloco_operatorio_intervencao as bi')
            ->join('intervencaos as i', 'i.id', '=', 'bi.intervencao_id')
            ->whereIn('i.nome', self::INTERVENCOES_COLORRETAIS)
            ->pluck('bi.bloco_operatorio_id')
            ->flip();

        $ativos = DB::table('internamentos')->whereNull('deleted_at')->pluck('id')->flip();

        return DB::table('bloco_operatorios')
            ->whereNull('deleted_at')
            ->whereBetween(DB::raw('year(data_de_inicio)'), [min($anos), max($anos)])
            ->get(['id', 'internamento_id', 'data_de_inicio', 'tipo_de_cirurgia_id', 'tipo_de_abordagem_id'])
            // blocos de internamentos apagados não contam
            ->filter(fn($b) => $ativos->has($b->internamento_id))
            ->map(fn($b) => (object) [
                'id' => $b->id,
                'ano' => (int) substr($b->data_de_inicio, 0, 4),
                'tipo' => $tipos[$b->tipo_de_cirurgia_id] ?? 'programada',
                'abordagem' => $abordagens[$b->tipo_de_abordagem_id] ?? null,
                'colorretal' => $colorretais->has($b->id),
                // pode ser null se o internamento entrou noutro ano (fora do período)
                'internamento' => $internamentos->get($b->internamento_id),
                'internamento_id' => $b->internamento_id,
            ])
            ->values();
    }

    private function tabelaCirurgias(array $anos, Collection $blocos): array
    {
        $porTipo = fn(array $tipos) => $this->porAno($anos, fn($ano) => $blocos->where('ano', $ano)->whereIn('tipo', $tipos)->count());

        return [
            'titulo' => 'Cirurgias',
            'linhas' => [
                $this->linha('Cirurgias de urgência', $porTipo(['urgencia'])),
                $this->linha('Cirurgias programadas', $porTipo(['programada'])),
                $this->linha('Cirurgia ambulatório', $porTipo(['ambulatorio'])),
                $this->linha('Total de cirurgias realizadas', $porTipo(['urgencia', 'programada', 'ambulatorio'])),
                $this->linha('Total de doentes operados', $this->porAno($anos, fn($ano) => $blocos->where('ano', $ano)->pluck('internamento_id')->unique()->count())),
            ],
        ];
    }

    private function tabelaInternamentos(array $anos, Collection $internamentos, Collection $blocos): array
    {
        // o relatório exclui a cirurgia de ambulatório dos internamentos
        $internados = $blocos->whereIn('tipo', ['urgencia', 'programada']);
        $doentes = fn($ano, array $tipos) => $internados->where('ano', $ano)->whereIn('tipo', $tipos)
            ->pluck('internamento')->filter()->unique('id');

        $internadosDoAno = fn($ano) => $internamentos->where('ano', $ano);
        $operadosFalecidos = fn($ano) => $doentes($ano, ['urgencia', 'programada'])->where('faleceu', true)->count();
        $linha = fn(string $titulo, callable $fn, string $formato = 'int') => $this->linha($titulo, $this->porAno($anos, $fn), $formato);

        return [
            'titulo' => 'Internamentos',
            'linhas' => [
                $linha('Total de doentes internados', fn($a) => $internadosDoAno($a)->count()),
                $linha('Mediana tempo de internamento', fn($a) => $this->mediana($internadosDoAno($a)->pluck('dias')), 'dec'),
                $linha('Total doentes operados', fn($a) => $doentes($a, ['urgencia', 'programada'])->count()),
                $linha('Total de cirurgias realizadas', fn($a) => $internados->where('ano', $a)->count()),
                $linha('Total de cirurgias realizadas de urgência', fn($a) => $internados->where('ano', $a)->where('tipo', 'urgencia')->count()),
                $linha('Total cirurgias realizadas programadas', fn($a) => $internados->where('ano', $a)->where('tipo', 'programada')->count()),
                $linha('Mediana doentes operados em programada', fn($a) => $this->mediana($doentes($a, ['programada'])->pluck('dias')), 'dec'),
                $linha('Mediana doentes operados em urgência', fn($a) => $this->mediana($doentes($a, ['urgencia'])->pluck('dias')), 'dec'),
                $linha('Morbilidade* %', fn($a) => $this->percentagem($internadosDoAno($a)->where('morbilidade', true)->count(), $internadosDoAno($a)->count()), 'pct'),
                $linha('Mortalidade %', fn($a) => $this->percentagem($operadosFalecidos($a), $internadosDoAno($a)->count()), 'pct'),
            ],
            'nota' => '* Morbilidade: doentes internados com complicações Clavien-Dindo ≥ 3A. Mortalidade: doentes operados falecidos / doentes internados.',
        ];
    }

    private function tabelaColorretal(array $anos, Collection $blocos): array
    {
        $doentes = fn($ano, ?string $tipo = null) => $blocos->where('ano', $ano)
            ->when($tipo === 'urgencia', fn($c) => $c->where('tipo', 'urgencia'))
            ->when($tipo === 'programada', fn($c) => $c->where('tipo', '!=', 'urgencia'))
            ->pluck('internamento')->filter()->unique('id');
        $cirurgias = fn($ano) => $blocos->where('ano', $ano);
        $taxa = fn(string $campo) => fn($a) => $this->percentagem(
            $cirurgias($a)->filter(fn($b) => $b->internamento?->$campo)->count(),
            $cirurgias($a)->count(),
        );
        $linha = fn(string $titulo, callable $fn, string $formato = 'int') => $this->linha($titulo, $this->porAno($anos, $fn), $formato);

        return [
            'titulo' => 'Cirurgia colorretal',
            'linhas' => [
                $linha('Total doentes operados', fn($a) => $doentes($a)->count()),
                $linha('Mediana tempo de internamento', fn($a) => $this->mediana($doentes($a)->pluck('dias')), 'dec'),
                $linha('Total de cirurgias realizadas', fn($a) => $cirurgias($a)->count()),
                $linha('Total de cirurgias realizadas de urgência', fn($a) => $cirurgias($a)->where('tipo', 'urgencia')->count()),
                $linha('Total cirurgias realizadas programadas', fn($a) => $cirurgias($a)->where('tipo', '!=', 'urgencia')->count()),
                $linha('Mediana doentes operados em programada', fn($a) => $this->mediana($doentes($a, 'programada')->pluck('dias')), 'dec'),
                $linha('Mediana doentes operados em urgência', fn($a) => $this->mediana($doentes($a, 'urgencia')->pluck('dias')), 'dec'),
                $linha('Morbilidade* %', $taxa('morbilidade'), 'pct'),
                $linha('Mortalidade %', $taxa('faleceu'), 'pct'),
                $linha('Deiscência %', $taxa('deiscencia'), 'pct'),
            ],
            'nota' => 'Percentagens calculadas sobre o total de cirurgias colorretais do ano.',
        ];
    }

    /** Número de vezes que cada intervenção colorretal foi realizada, por ano. */
    private function tabelaIntervencoes(array $anos): array
    {
        $contagens = DB::table('bloco_operatorio_intervencao as bi')
            ->join('intervencaos as i', 'i.id', '=', 'bi.intervencao_id')
            ->join('bloco_operatorios as b', 'b.id', '=', 'bi.bloco_operatorio_id')
            ->join('internamentos as n', 'n.id', '=', 'b.internamento_id')
            ->whereNull('b.deleted_at')
            ->whereNull('n.deleted_at')
            ->whereIn('i.nome', self::INTERVENCOES_COLORRETAIS)
            ->whereBetween(DB::raw('year(b.data_de_inicio)'), [min($anos), max($anos)])
            ->groupBy('i.nome', DB::raw('year(b.data_de_inicio)'))
            ->get(['i.nome', DB::raw('year(b.data_de_inicio) as ano'), DB::raw('count(*) as total')])
            ->groupBy('nome');

        $linhas = collect(self::INTERVENCOES_COLORRETAIS)->map(fn($nome) => $this->linha(
            $nome,
            $this->porAno($anos, fn($a) => (int) ($contagens->get($nome)?->firstWhere('ano', $a)->total ?? 0)),
        ));

        $linhas->push($this->linha('Total', $this->porAno($anos, fn($a) => $linhas->sum(fn($l) => $l['valores'][$a]))));

        return ['titulo' => 'Intervenções colorretais', 'linhas' => $linhas->all()];
    }

    private function tabelaAbordagem(array $anos, Collection $colorretais): array
    {
        return [
            'titulo' => 'Abordagem cirúrgica (colorretal)',
            'linhas' => collect(self::ABORDAGENS)->map(fn($abordagem) => $this->linha(
                $abordagem,
                $this->porAno($anos, fn($a) => $this->percentagem(
                    $colorretais->where('ano', $a)->where('abordagem', $abordagem)->count(),
                    $colorretais->where('ano', $a)->count(),
                )),
                'pct',
            ))->all(),
        ];
    }

    private function linha(string $titulo, array $valores, string $formato = 'int'): array
    {
        return ['titulo' => $titulo, 'valores' => $valores, 'formato' => $formato];
    }

    /** @return array<int, mixed> valores indexados por ano */
    private function porAno(array $anos, callable $fn): array
    {
        return collect($anos)->mapWithKeys(fn($ano) => [$ano => $fn($ano)])->all();
    }

    private function mediana(Collection $valores): ?float
    {
        $v = $valores->filter(fn($x) => $x !== null)->sort()->values();
        $n = $v->count();

        if ($n === 0) {
            return null;
        }

        return $n % 2 ? (float) $v[intdiv($n, 2)] : ($v[$n / 2 - 1] + $v[$n / 2]) / 2;
    }

    private function percentagem(int $parte, int $total): ?float
    {
        return $total > 0 ? round($parte / $total * 100, 2) : null;
    }
}
