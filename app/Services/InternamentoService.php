<?php

namespace App\Services;

use App\Models\Internamento;
use App\Models\OrigemDoInternamento;
use App\Models\Utente;
use App\ViewModels\UtenteViewModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class InternamentoService
{
    /** Relações necessárias para {@see mapInternamentoCompleto()}. */
    private const RELACOES_INTERNAMENTO_COMPLETO = [
        'utente',
        'responsavel',
        'localizacao',
        'diagnosticos',
        'complicacaos',
        'blocoOperatorios.tipoDeCirurgia',
        'blocoOperatorios.blocoOperatorioCRS',
        'blocoOperatorios.blocoOperatorioIntervencoes.intervencao',
        'blocoOperatorios.blocoOperatorioIntervencoes.descricao',
    ];

    public function paginate(int $perPage = 15, ?string $search = null, bool $minhaEquipa = false, ?string $equipa = null): LengthAwarePaginator
    {
        return Internamento::query()
            ->where('data_de_saida', null)
            ->with(self::RELACOES_INTERNAMENTO_COMPLETO)
            ->when($search, fn($query) => $query->where(function ($query) use ($search) {
                $query
                    ->where('cama', 'like', "%{$search}%")
                    ->orWhere('motivo_internamento', 'like', "%{$search}%")
                    ->orWhereHas('utente', fn($utenteQuery) => $utenteQuery
                        ->where('nome', 'like', "%{$search}%")
                        ->orWhere('numero_processo', 'like', "%{$search}%"));
            }))
            ->when($minhaEquipa && $equipa, fn($query) => $query->whereHas('responsavel', fn($responsavelQuery) => $responsavelQuery->where('equipa', $equipa)))
            ->join('localizacaos', 'localizacaos.id', '=', 'internamentos.localizacao_id')
            ->orderBy('localizacaos.nome')
            ->orderBy('internamentos.cama')
            ->select('internamentos.*')
            ->paginate($perPage)
            ->withQueryString()
            ->through(fn (Internamento $internamento) => $this->mapInternamentoCompleto($internamento));
    }

    /**
     * Representação completa de um internamento, incluindo tudo o que o formulário de
     * edição (CreateOrUpdateInternamentoModal) precisa para pré-preencher corretamente
     * — sem isto, gravar o formulário apagava silenciosamente campos já preenchidos.
     */
    private function mapInternamentoCompleto(Internamento $internamento): array
    {
        return [
            'id' => $internamento->id,
            'utente_id' => $internamento->utente_id,
            'nome_curto' => $internamento->utente?->nome_curto ?? 'N/A',
            'nome' => $internamento->utente?->nome ?? 'N/A',
            'numero_processo' => $internamento->utente?->numero_processo ?? 'N/A',
            'cama' => $internamento->cama ?? 'N/A',
            'data_de_entrada' => $internamento->data_de_entrada->format('Y-m-d') ?? 'N/A',
            'dias_desde_entrada' => $internamento->data_de_entrada ? (int) round($internamento->data_de_entrada->diffInDays(now())) : null,
            'origem_do_internamento_id' => $internamento->origem_do_internamento_id,
            'data_de_alta' => $internamento->data_de_alta?->format('Y-m-d'),
            'data_de_saida' => $internamento->data_de_saida?->format('Y-m-d'),
            'estado_da_alta_id' => $internamento->estado_da_alta_id,
            'clavien_dindo_id' => $internamento->clavien_dindo_id,
            'destino_id' => $internamento->destino_id,
            'caso_social_id' => $internamento->caso_social_id,
            'bloquear_tabela' => $internamento->bloquear_tabela,
            'motivo_internamento' => $internamento->motivo_internamento ?? 'N/A',
            'observacoes' => $internamento->observacoes ?? null,
            'comentarios' => $internamento->comentarios,
            'responsavel_id' => $internamento->responsavel_id ?? 'N/A',
            'responsavel' => $internamento->responsavel->abrev ?? 'N/A',
            'localizacao_id' => $internamento->localizacao_id,
            'localizacao' => $internamento->localizacao->nome ?? 'Sem localização',
            'dias_internamento' => $internamento->dias_internamento ?? 'N/A',
            'diagnosticos' => $internamento->diagnosticos->map(function ($diagnostico) {
                return [
                    'id' => $diagnostico->id,
                    'nome' => $diagnostico->nome,
                ];
            }),
            'complicacaos' => $internamento->complicacaos->map(function ($complicacao) {
                return [
                    'id' => $complicacao->id,
                    'nome' => $complicacao->nome,
                ];
            }),
            'bloco_operatorios' => $internamento->blocoOperatorios->map(function ($bloco) {
                return [
                    'id' => $bloco->id,
                    'data_de_inicio' => $bloco->data_de_inicio?->format('Y-m-d'),
                    'tipo_de_cirurgia' => $bloco->tipoDeCirurgia?->nome,
                    'tipo_de_cirurgia_id' => $bloco->tipo_de_cirurgia_id,
                    'tipo_de_abordagem_id' => $bloco->tipo_de_abordagem_id,
                    're_intervencao_nao_programada_id' => $bloco->re_intervencao_nao_programada_id,
                    'resseccao_de_orgao' => $bloco->resseccao_de_orgao,
                    'causa_de_conversao' => $bloco->causa_de_conversao,
                    'comentarios' => $bloco->comentarios,
                    // intervenções concretas realizadas neste bloco (linhas da pivot, cada uma com id próprio)
                    'intervencoes' => $bloco->blocoOperatorioIntervencoes->map(function ($linha) {
                        return [
                            'pivot_id' => $linha->id,
                            'intervencao_id' => $linha->intervencao_id,
                            'nome' => $linha->intervencao?->nome,
                            'centro_de_referencia' => (bool) $linha->intervencao?->centro_de_referencia,
                            'cirurgia_de_ressecao' => (bool) $linha->intervencao?->cirurgia_de_ressecao,
                            'descricao' => $linha->descricao ? [
                                'id' => $linha->descricao->id,
                                'anastemose_modo_id' => $linha->descricao->anastemose_modo_id,
                                'anastemose_via_id' => $linha->descricao->anastemose_via_id,
                                'anastemose_sentido_id' => $linha->descricao->anastemose_sentido_id,
                                'tipo_de_reconstrucao_id' => $linha->descricao->tipo_de_reconstrucao_id,
                                'reforco_anastemose' => $linha->descricao->reforco_anastemose,
                                'localizacao_anastemose_id' => $linha->descricao->localizacao_anastemose_id,
                                'confirmacao_anastemose_id' => $linha->descricao->confirmacao_anastemose_id,
                                'libertacao_angulo' => $linha->descricao->libertacao_angulo,
                                'numero_cargas' => $linha->descricao->numero_cargas,
                                'distancia_linha_pectinea_anastemose' => $linha->descricao->distancia_linha_pectinea_anastemose,
                                'distancia_linha_pectinea_tumor' => $linha->descricao->distancia_linha_pectinea_tumor,
                                'qualidade_peca_operatoria_id' => $linha->descricao->qualidade_peca_operatoria_id,
                                'comentarios' => $linha->descricao->comentarios,
                            ] : null,
                        ];
                    }),
                    'bloco_operatorio_c_r_s' => $bloco->blocoOperatorioCRS ? [
                        'id' => $bloco->blocoOperatorioCRS->id,
                        'experiencia_cirurgiao_id' => $bloco->blocoOperatorioCRS->experiencia_cirurgiao_id,
                        'asa' => $bloco->blocoOperatorioCRS->asa,
                        'mortalidade' => $bloco->blocoOperatorioCRS->mortalidade,
                        'score_fisiologico' => $bloco->blocoOperatorioCRS->score_fisiologico,
                        'score_gravidade_cirurgico' => $bloco->blocoOperatorioCRS->score_gravidade_cirurgico,
                        'preparacao_intestinal_id' => $bloco->blocoOperatorioCRS->preparacao_intestinal_id,
                        'intencao_id' => $bloco->blocoOperatorioCRS->intencao_id,
                        'paleativa_causa' => $bloco->blocoOperatorioCRS->paleativa_causa,
                        'duracao' => $bloco->blocoOperatorioCRS->duracao,
                        'estoma_de_protecao_id' => $bloco->blocoOperatorioCRS->estoma_de_protecao_id,
                        'local_extracao_peca_id' => $bloco->blocoOperatorioCRS->local_extracao_peca_id,
                        'tipo_de_dreno_id' => $bloco->blocoOperatorioCRS->tipo_de_dreno_id,
                        'resseccao_multi_orgao' => $bloco->blocoOperatorioCRS->resseccao_multi_orgao,
                        'resseccao_multi_orgao_quais' => $bloco->blocoOperatorioCRS->resseccao_multi_orgao_quais,
                        'aderencia_id' => $bloco->blocoOperatorioCRS->aderencia_id,
                        'tipo_de_resseccao_id' => $bloco->blocoOperatorioCRS->tipo_de_resseccao_id,
                        'neoplasia_residual_id' => $bloco->blocoOperatorioCRS->neoplasia_residual_id,
                        'perdas_hematica_id' => $bloco->blocoOperatorioCRS->perdas_hematica_id,
                        'transfusao_intra_operatoria' => $bloco->blocoOperatorioCRS->transfusao_intra_operatoria,
                        'unidades_globulos' => $bloco->blocoOperatorioCRS->unidades_globulos,
                        'protector_de_parede' => $bloco->blocoOperatorioCRS->protector_de_parede,
                        'complicacoes' => $bloco->blocoOperatorioCRS->complicacoes,
                        'complicacoes_quais' => $bloco->blocoOperatorioCRS->complicacoes_quais,
                    ] : null,
                ];
            }),
            'utente' => new UtenteViewModel($internamento->utente),
        ];
    }

    public function search(array $filters)
    {
        return Utente::query()
            ->with('centroDeReferencia')
            ->when(
                $filters['search'] ?? null,
                function ($query, $search) {
                    $search = trim($search);

                    $query->where(function ($query) use ($search) {
                        $query
                            ->where('nome', 'like', "%{$search}%")
                            ->orWhere(
                                'numero_processo',
                                'like',
                                "%{$search}%"
                            );
                    });
                }
            )
            ->orderBy('nome')
            ->paginate(15)
            ->withQueryString()->through(function ($internamento) {
                return [
                    'id' => $internamento->id,
                    'nome_paciente' => $internamento->utente?->nome ?? 'N/A',
                    'numero_processo' => $internamento->utente?->numero_processo ?? 'N/A',
                    'cama' => $internamento->cama ?? 'N/A',
                    'data_internamento' => $internamento->data_de_entrada ?? 'N/A',
                    'data_alta' => $internamento->data_alta ?? 'N/A',
                    'motivo_internamento' => $internamento->motivo_internamento ?? 'N/A',
                    'observacoes' => $internamento->observacoes ?? 'N/A',
                    'responsavel' => $internamento->responsavel->abrev ?? 'N/A',
                ];
            });
    }


    public function forUtente(int $utenteId, ?string $search = null)
    {
        return Internamento::query()
            ->where('utente_id', $utenteId)
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('cama', 'like', "%{$search}%")
                        ->orWhere(
                            'motivo_internamento',
                            'like',
                            "%{$search}%"
                        );
                });
            })
            ->orderBy('data_de_entrada', 'desc')
            ->paginate(15)
            ->withQueryString()->through(function ($internamento) {
                return [
                    'id' => $internamento->id,
                    'nome_curto' => $internamento->utente?->nome_curto ?? 'N/A',
                    'nome_paciente' => $internamento->utente?->nome ?? 'N/A',
                    'numero_processo' => $internamento->utente?->numero_processo ?? 'N/A',
                    'cama' => $internamento->cama ?? 'N/A',
                    'data_de_entrada' => $internamento->data_de_entrada ?? 'N/A',
                    'data_de_alta' => $internamento->data_de_alta ?? 'N/A',
                    'data_de_saida' => $internamento->data_de_saida ?? 'N/A',
                    'estadoDaAlta' => $internamento->estado_da_alta_id ?? 'N/A',
                    'motivo_internamento' => $internamento->motivo_internamento ?? 'N/A',
                    'observacoes' => $internamento->observacoes ?? 'N/A',
                    'responsavel' => $internamento->responsavel->abrev ?? 'N/A',
                    'centro_de_referencia' => $internamento->utente->centroDeReferencia?->id ?? 'N/A',
                ];
            });
    }

    public function getOrigensInternamento()
    {
        return OrigemDoInternamento::query()
            ->orderBy('nome')
            ->get(['id', 'nome']);
    }

    public function getEstadosAlta()
    {
        return \App\Models\EstadoDaAlta::query()
            ->orderBy('nome')
            ->get(['id', 'nome']);
    }

    public function getResponsaveis()
    {
        return \App\Models\User::query()
            ->whereActivo(true)
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    public function getClavienDindo()
    {
        return \App\Models\ClavienDindo::query()
            ->orderBy('nome')
            ->get(['id', 'nome']);
    }

    public function getDestinos()
    {
        return \App\Models\Destino::query()
            ->orderBy('nome')
            ->get(['id', 'nome']);
    }

    public function getCasosSociais()
    {
        return \App\Models\CasoSocial::query()
            ->orderBy('nome')
            ->get(['id', 'nome']);
    }

    // EstadoDaAlta: 1 = Pendente, 2 = Concluida, 3 = Outro
    private const ESTADO_ALTA_PENDENTE = 1;
    private const ESTADO_ALTA_CONCLUIDA = 2;

    // ClavienDindo: 8 = "A aguardar ..." (por classificar)
    private const CLAVIEN_DINDO_A_AGUARDAR = 8;

    public function create(array $data): Internamento
    {
        $complicacaoIds = $data['complicacao_ids'] ?? [];
        unset($data['complicacao_ids']);

        $internamento = Internamento::create($data);
        $internamento->complicacaos()->sync($complicacaoIds);

        $this->atualizarEstadoDaAlta($internamento);

        return $internamento;
    }

    public function update(Internamento $internamento, array $data): Internamento
    {
        if (array_key_exists('complicacao_ids', $data)) {
            $internamento->complicacaos()->sync($data['complicacao_ids'] ?? []);
            unset($data['complicacao_ids']);
        }

        $internamento->update($data);

        $this->atualizarEstadoDaAlta($internamento);

        return $internamento;
    }

    /**
     * O estado da alta não é escolhido manualmente: um doente operado (com pelo menos um bloco
     * operatório) está sempre "Pendente"; um doente não operado está sempre "Concluída". Chamado
     * sempre que o internamento é gravado e sempre que os seus blocos operatórios mudam.
     */
    public function atualizarEstadoDaAlta(Internamento $internamento): void
    {
        $estado = $internamento->blocoOperatorios()->exists() ? self::ESTADO_ALTA_PENDENTE : self::ESTADO_ALTA_CONCLUIDA;

        if ($internamento->estado_da_alta_id !== $estado) {
            $internamento->update(['estado_da_alta_id' => $estado]);
        }
    }

    /**
     * Doentes operados cuja janela de morbilidade cirúrgica aos 30 dias já passou (data de alta há
     * mais de 30 dias) e cujas complicações (Clavien-Dindo) ainda não foram classificadas — nem que
     * seja para confirmar que não houve complicações. Não depende de o doente já ter saído ou não.
     * Só considera altas a partir de 2023 (histórico anterior fica fora desta lista de trabalho).
     */
    public function getPendentes(?string $equipa = null, bool $minhaEquipa = false, int $perPage = 20): LengthAwarePaginator
    {
        return Internamento::query()
            ->whereHas('blocoOperatorios')
            ->whereNotNull('data_de_alta')
            ->whereDate('data_de_alta', '>=', '2024-01-01')
            ->whereDate('data_de_alta', '<', now()->subDays(30)->toDateString())
            ->where(fn ($query) => $query
                ->whereNull('clavien_dindo_id')
                ->orWhere('clavien_dindo_id', self::CLAVIEN_DINDO_A_AGUARDAR))
            ->with(self::RELACOES_INTERNAMENTO_COMPLETO)
            ->when($minhaEquipa && $equipa, fn ($query) => $query->whereHas('responsavel', fn ($responsavelQuery) => $responsavelQuery->where('equipa', $equipa)))
            ->orderBy('data_de_alta')
            ->paginate($perPage)
            ->withQueryString()
            ->through(fn (Internamento $internamento) => $this->mapInternamentoCompleto($internamento));
    }

    public function getLocalizacoes()
    {
        return \App\Models\Localizacao::query()
            ->orderBy('nome')
            ->get(['id', 'nome']);
    }

    public function getOrigensDaReferenciacao()
    {
        return \App\Models\OrigemDaReferenciacao::query()
            ->orderBy('nome')
            ->get(['id', 'nome']);
    }

    public function getTiposDeCirurgia()
    {
        return \App\Models\TipoDeCirurgia::query()
            ->orderBy('nome')
            ->get(['id', 'nome']);
    }

    public function getTiposDeAbordagem()
    {
        return \App\Models\TipoDeAbordagem::query()
            ->orderBy('nome')
            ->get(['id', 'nome']);
    }

    public function getReIntervencoesNaoProgramadas()
    {
        return \App\Models\ReIntervencaoNaoProgramada::query()
            ->orderBy('nome')
            ->get(['id', 'nome']);
    }

    public function getIntervencoes()
    {
        return \App\Models\Intervencao::query()
            ->orderBy('nome')
            ->get(['id', 'nome', 'centro_de_referencia', 'cirurgia_de_ressecao']);
    }

    public function getIntencoes()
    {
        return \App\Models\Intencao::query()->orderBy('nome')->get(['id', 'nome']);
    }

    public function getEstomasDeProtecao()
    {
        return \App\Models\EstomaDeProtecao::query()->orderBy('nome')->get(['id', 'nome']);
    }

    public function getLocaisExtracaoPeca()
    {
        return \App\Models\LocalExtracaoPeca::query()->orderBy('nome')->get(['id', 'nome']);
    }

    public function getTiposDeDreno()
    {
        return \App\Models\TipoDeDreno::query()->orderBy('nome')->get(['id', 'nome']);
    }

    public function getAderencias()
    {
        return \App\Models\Aderencia::query()->orderBy('nome')->get(['id', 'nome']);
    }

    public function getTiposDeResseccao()
    {
        return \App\Models\TipoDeResseccao::query()->orderBy('nome')->get(['id', 'nome']);
    }

    public function getNeoplasiasResiduais()
    {
        return \App\Models\NeoplasiaResidual::query()->orderBy('nome')->get(['id', 'nome']);
    }

    public function getPerdasHematicas()
    {
        return \App\Models\PerdaHematica::query()->orderBy('nome')->get(['id', 'nome']);
    }

    public function getAnastemoseModos()
    {
        return \App\Models\AnastemoseModo::query()->orderBy('nome')->get(['id', 'nome']);
    }

    public function getAnastemoseVias()
    {
        return \App\Models\AnastemoseVia::query()->orderBy('nome')->get(['id', 'nome']);
    }

    public function getAnastemoseSentidos()
    {
        return \App\Models\AnastemoseSentido::query()->orderBy('nome')->get(['id', 'nome']);
    }

    public function getTiposDeReconstrucao()
    {
        return \App\Models\TipoDeReconstrucao::query()->orderBy('nome')->get(['id', 'nome']);
    }

    public function getLocalizacoesAnastemose()
    {
        return \App\Models\LocalizacaoAnastemose::query()->orderBy('nome')->get(['id', 'nome']);
    }

    public function getConfirmacoesAnastemose()
    {
        return \App\Models\ConfirmacaoAnastemose::query()->orderBy('nome')->get(['id', 'nome']);
    }

    public function getQualidadesPecaOperatoria()
    {
        return \App\Models\QualidadePecaOperatoria::query()->orderBy('nome')->get(['id', 'nome']);
    }

    public function addDiagnostico(Internamento $internamento, int $diagnosticoId)
    {
        $internamento->diagnosticos()->attach($diagnosticoId);
    }

    // remove diagnostico from internamento
    public function removeDiagnostico(Internamento $internamento, int $diagnosticoId)
    {
         $internamento->diagnosticos()->detach($diagnosticoId);
    }

    // add complicacao to internamento
    public function addComplicacao(Internamento $internamento, int $complicacaoId)
    {
        $internamento->complicacaos()->syncWithoutDetaching($complicacaoId);
    }

    // remove complicacao from internamento
    public function removeComplicacao(Internamento $internamento, int $complicacaoId)
    {
        $internamento->complicacaos()->detach($complicacaoId);
    }
}
