import { AppInputField } from '@/components/app/app-input-field';
import { AppSelectField } from '@/components/app/app-input-select';
import { AppModalForm } from '@/components/app/app-modal-form';
import AppMultiSelect from '@/components/app/app-multi-select';
import { useCrudForm } from '@/hooks/use-crud-form';
import type { InternamentoOptions, LookupOption, ResolucaoComplicacaoOption } from '@/types/internamento';
import type { InternamentoItem } from '@/types/type';
import { X } from 'lucide-react';
import { FormEvent, useEffect, useState } from 'react';

interface Props {
    open: boolean;
    onClose: () => void;
    utenteId: number | null;
    internamento?: InternamentoItem | null;
    internamentoOptions: InternamentoOptions;
    complicacoesOptions?: LookupOption[];
    resolucoesComplicacaoOptions?: ResolucaoComplicacaoOption[];
    onSubmit?: () => void;
}

/**
 * Grau Clavien-Dindo mais grave entre as resoluções escolhidas (usado só como sugestão — a
 * classificação final é sempre uma decisão clínica). A ordem de gravidade corresponde à ordem
 * dos ids (1 = Grau 1 ... 7 = Grau 5); o id 8 ("A aguardar...") não é uma gravidade e é ignorado.
 */
const AGUARDAR_CLASSIFICACAO_ID = 8;

function sugerirClavienDindoId(
    complicacoesSelecionadas: { id: string; resolucao_ids: string[] }[],
    resolucoesComplicacaoOptions: ResolucaoComplicacaoOption[],
): number | null {
    const graus = complicacoesSelecionadas
        .flatMap((complicacao) => complicacao.resolucao_ids)
        .map((resolucaoId) => resolucoesComplicacaoOptions.find((option) => String(option.id) === resolucaoId)?.clavien_dindo_id)
        .filter((id): id is number => id != null && id !== AGUARDAR_CLASSIFICACAO_ID);

    return graus.length > 0 ? Math.max(...graus) : null;
}

/** Dias corridos desde uma data (string "YYYY-MM-DD") até hoje. */
function diasDesde(data: string | null | undefined): number | null {
    if (!data) {
        return null;
    }

    const inicio = new Date(data);

    if (Number.isNaN(inicio.getTime())) {
        return null;
    }

    return Math.floor((Date.now() - inicio.getTime()) / (1000 * 60 * 60 * 24));
}

const emptyOptions: InternamentoOptions = {
    origensInternamento: [],
    estadosAlta: [],
    responsaveis: [],
    clavienDindo: [],
    destinos: [],
    casosSociais: [],
    localizacoes: [],
};

const emptyForm = (utenteId: number | null): InternamentoItem => ({
    utente_id: utenteId ?? 0,
    cama: '',
    origem_do_internamento_id: null,
    data_de_entrada: '',
    data_de_alta: '',
    data_de_saida: '',
    estado_da_alta_id: null,
    responsavel_id: null,
    motivo_internamento: '',
    clavien_dindo_id: null,
    destino_id: null,
    caso_social_id: null,
    bloquear_tabela: false,
    comentarios: '',
    localizacao_id: null,
    complicacoes: [],
});

export default function CreateOrUpdateInternamentoModal({
    open,
    onClose,
    utenteId,
    internamento,
    internamentoOptions,
    complicacoesOptions = [],
    resolucoesComplicacaoOptions = [],
    onSubmit,
}: Props) {
    const isEdit = !!internamento;
    const options = internamentoOptions ?? emptyOptions;

    const [tentouSubmeter, setTentouSubmeter] = useState(false);

    const { form, errors, loading, updateField, resetForm, submit } = useCrudForm<InternamentoItem>(emptyForm(utenteId), {
        url:
            isEdit && internamento
                ? route('internamentos.update', (internamento as InternamentoItem & { id: number }).id)
                : route('internamentos.store'),
        isEditing: isEdit,
        successMessage: isEdit ? 'Internamento atualizado com sucesso.' : 'Internamento criado com sucesso.',
        onSuccess: () => {
            onSubmit?.();
            onClose();
        },
    });

    useEffect(() => {
        if (!open) {
            return;
        }

        if (internamento) {
            resetForm({
                utente_id: internamento.utente_id,

                cama: String(internamento.cama ?? ''),

                origem_do_internamento_id: internamento.origem_do_internamento_id != null ? Number(internamento.origem_do_internamento_id) : null,

                data_de_entrada: internamento.data_de_entrada ?? '',

                data_de_alta: internamento.data_de_alta ?? '',

                data_de_saida: internamento.data_de_saida ?? '',

                estado_da_alta_id: internamento.estado_da_alta_id != null ? Number(internamento.estado_da_alta_id) : null,

                responsavel_id: internamento.responsavel_id != null ? Number(internamento.responsavel_id) : null,

                motivo_internamento: internamento.motivo_internamento ?? '',

                clavien_dindo_id: internamento.clavien_dindo_id != null ? Number(internamento.clavien_dindo_id) : null,

                destino_id: internamento.destino_id != null ? Number(internamento.destino_id) : null,

                caso_social_id: internamento.caso_social_id != null ? Number(internamento.caso_social_id) : null,

                bloquear_tabela: internamento.bloquear_tabela ?? false,

                comentarios: internamento.comentarios ?? '',

                localizacao_id: internamento.localizacao_id != null ? Number(internamento.localizacao_id) : null,

                complicacoes:
                    internamento.complicacaos?.map((complicacao) => ({
                        id: String(complicacao.id),
                        resolucao_ids: (complicacao.resolucao_ids ?? complicacao.pivot?.resolucao ?? []).map(String),
                    })) ?? [],
            });
        } else {
            resetForm(emptyForm(utenteId));
        }
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [open, internamento, utenteId]);

    // Antes de passarem 30 dias sobre a alta, o estado é sempre calculado automaticamente (ver
    // InternamentoService::atualizarEstadoDaAlta): operado -> Pendente, não operado -> Concluída.
    // Depois de passarem 30 dias, fica desbloqueado para poder ser definido manualmente (ex:
    // "Concluída" depois de classificar o Clavien-Dindo), para se poder completar o processo.
    const operado = (internamento?.bloco_operatorios?.length ?? 0) > 0;
    const estadoAltaLabel = operado ? 'Pendente (doente operado)' : 'Concluída (doente não operado)';

    // Clavien-Dindo e a edição manual do estado da alta só ficam disponíveis depois de passarem
    // 30 dias sobre a alta (morbilidade cirúrgica aos 30 dias).
    const diasDesdeAlta = diasDesde(form.data_de_alta);
    const clavienDindoDisponivel = diasDesdeAlta !== null && diasDesdeAlta >= 30;
    const estadoAltaDesbloqueado = clavienDindoDisponivel;

    // Complicações selecionadas + resolução(ões) de cada uma (guardadas na pivot complicacao_internamento).
    const complicacoesSelecionadas = form.complicacoes ?? [];

    const handleComplicacoesChange = (ids: string[]) => {
        updateField(
            'complicacoes',
            ids.map((id) => complicacoesSelecionadas.find((complicacao) => complicacao.id === id) ?? { id, resolucao_ids: [] }),
        );
    };

    const handleResolucaoIdsChange = (complicacaoId: string, resolucaoIds: string[]) => {
        updateField(
            'complicacoes',
            complicacoesSelecionadas.map((complicacao) =>
                complicacao.id === complicacaoId ? { ...complicacao, resolucao_ids: resolucaoIds } : complicacao,
            ),
        );
    };

    const handleRemoverComplicacao = (complicacaoId: string) => {
        updateField(
            'complicacoes',
            complicacoesSelecionadas.filter((complicacao) => complicacao.id !== complicacaoId),
        );
    };

    // Toda a complicação identificada tem de ficar com pelo menos uma resolução escolhida.
    const complicacoesSemResolucao = complicacoesSelecionadas.filter((complicacao) => complicacao.resolucao_ids.length === 0);
    const complicacoesInvalidas = tentouSubmeter && complicacoesSemResolucao.length > 0;

    const handleSubmit = (event: FormEvent<HTMLFormElement>) => {
        if (complicacoesSemResolucao.length > 0) {
            event.preventDefault();
            setTentouSubmeter(true);
            return;
        }

        submit(event);
    };

    // Sugestão de Clavien-Dindo com base na resolução mais grave escolhida (nunca aplicada automaticamente).
    const clavienDindoSugeridoId = sugerirClavienDindoId(complicacoesSelecionadas, resolucoesComplicacaoOptions);
    const clavienDindoSugerido = clavienDindoSugeridoId != null ? options.clavienDindo.find((option) => option.id === clavienDindoSugeridoId) : null;

    if (!open) {
        return null;
    }

    return (
        <AppModalForm
            open={open}
            title={isEdit ? 'Editar Internamento' : 'Novo Internamento'}
            description={isEdit ? 'Atualize os dados do internamento.' : 'Introduza os dados do novo internamento.'}
            onClose={onClose}
            onSubmit={handleSubmit}
            loading={loading}
            maxWidth="5xl"
            submitLabel={isEdit ? 'Guardar alterações' : 'Criar internamento'}
        >
            <div className="grid gap-2 md:grid-cols-2">
                <AppInputField label="Cama" value={form.cama ?? ''} onChange={(value) => updateField('cama', value)} error={errors.cama} />
                <AppSelectField
                    label="Localização"
                    value={form.localizacao_id ?? ''}
                    onChange={(value) => updateField('localizacao_id', value === '' ? null : Number(value))}
                    error={errors.localizacao_id}
                    options={options.localizacoes.map((user) => ({
                        value: user.id,
                        label: user.nome,
                    }))}
                />
                <AppSelectField
                    label="Origem do internamento"
                    value={form.origem_do_internamento_id ?? ''}
                    onChange={(value) => updateField('origem_do_internamento_id', value === '' ? null : Number(value))}
                    error={errors.origem_do_internamento_id}
                    options={options.origensInternamento.map((option) => ({
                        value: option.id,
                        label: option.nome,
                    }))}
                />

                <AppInputField
                    label="Data de entrada"
                    type="date"
                    value={form.data_de_entrada}
                    onChange={(value) => updateField('data_de_entrada', value)}
                    error={errors.data_de_entrada}
                />
                <AppSelectField
                    label="Responsável"
                    value={form.responsavel_id ?? ''}
                    onChange={(value) => updateField('responsavel_id', value === '' ? null : Number(value))}
                    error={errors.responsavel_id}
                    options={options.responsaveis.map((user) => ({
                        value: user.id,
                        label: user.name,
                    }))}
                />
                <AppInputField
                    label="Motivo do internamento"
                    value={form.motivo_internamento}
                    onChange={(value) => updateField('motivo_internamento', value)}
                    error={errors.motivo_internamento}
                    placeholder="Indique o motivo do internamento"
                />
            </div>

            <hr />
            {internamento && (
                <div className="grid gap-2 md:grid-cols-2">
                    <AppInputField
                        label="Data de alta"
                        type="date"
                        value={form.data_de_alta ?? ''}
                        onChange={(value) => updateField('data_de_alta', value)}
                        error={errors.data_de_alta}
                    />

                    <AppInputField
                        label="Data de saída"
                        type="date"
                        value={form.data_de_saida ?? ''}
                        onChange={(value) => updateField('data_de_saida', value)}
                        error={errors.data_de_saida}
                    />

                    {estadoAltaDesbloqueado ? (
                        <AppSelectField
                            label="Estado da alta"
                            value={form.estado_da_alta_id ?? ''}
                            onChange={(value) => updateField('estado_da_alta_id', value === '' ? null : Number(value))}
                            error={errors.estado_da_alta_id}
                            options={options.estadosAlta.map((option) => ({
                                value: option.id,
                                label: option.nome,
                            }))}
                        />
                    ) : (
                        <div>
                            <span className="mb-2 block text-sm font-medium">Estado da alta</span>
                            <div className="border-input bg-muted text-muted-foreground flex h-10 items-center rounded-md border px-3 text-sm">
                                {estadoAltaLabel}
                            </div>
                            <p className="text-muted-foreground mt-1 text-xs">
                                Calculado automaticamente até passarem 30 dias sobre a alta; a partir daí fica disponível para completar manualmente.
                            </p>
                        </div>
                    )}
                    {operado ? (
                        clavienDindoDisponivel ? (
                            <div>
                                <AppSelectField
                                    label="Clavien-Dindo"
                                    value={form.clavien_dindo_id ?? ''}
                                    onChange={(value) => updateField('clavien_dindo_id', value === '' ? null : Number(value))}
                                    error={errors.clavien_dindo_id}
                                    options={options.clavienDindo.map((option) => ({
                                        value: option.id,
                                        label: option.nome,
                                    }))}
                                />
                                {clavienDindoSugerido && form.clavien_dindo_id !== clavienDindoSugerido.id && (
                                    <p className="mt-1 flex flex-wrap items-center gap-1 text-xs text-blue-700 dark:text-blue-400">
                                        Sugestão com base nas resoluções: <strong>{clavienDindoSugerido.nome}</strong>
                                        <button
                                            type="button"
                                            className="font-medium underline underline-offset-2 hover:text-blue-900 dark:hover:text-blue-300"
                                            onClick={() => updateField('clavien_dindo_id', clavienDindoSugerido.id)}
                                        >
                                            Aplicar
                                        </button>
                                    </p>
                                )}
                            </div>
                        ) : (
                            <div>
                                <span className="mb-2 block text-sm font-medium">Clavien-Dindo</span>
                                <div className="border-input bg-muted text-muted-foreground flex h-10 items-center rounded-md border px-3 text-sm">
                                    Ainda não disponível
                                </div>
                                <p className="text-muted-foreground mt-1 text-xs">
                                    {form.data_de_alta
                                        ? `Disponível a partir de 30 dias após a alta (faltam ${30 - (diasDesdeAlta ?? 0)} dia(s)).`
                                        : 'Disponível 30 dias depois de preencher a data de alta (morbilidade cirúrgica aos 30 dias).'}
                                </p>
                            </div>
                        )
                    ) : null}

                    <AppSelectField
                        label="Destino"
                        value={form.destino_id ?? ''}
                        onChange={(value) => updateField('destino_id', value === '' ? null : Number(value))}
                        error={errors.destino_id}
                        options={options.destinos.map((option) => ({
                            value: option.id,
                            label: option.nome,
                        }))}
                    />

                    <AppSelectField
                        label="Caso social"
                        value={form.caso_social_id ?? ''}
                        onChange={(value) => updateField('caso_social_id', value === '' ? null : Number(value))}
                        error={errors.caso_social_id}
                        options={options.casosSociais.map((option) => ({
                            value: option.id,
                            label: option.nome,
                        }))}
                    />

                    <div className="space-y-4 md:col-span-2">
                        <AppMultiSelect
                            label="Complicações"
                            value={complicacoesSelecionadas.map((complicacao) => complicacao.id)}
                            onChange={handleComplicacoesChange}
                            options={complicacoesOptions.map((option) => ({
                                value: String(option.id),
                                label: option.nome,
                            }))}
                            placeholder="Selecionar complicações..."
                            searchPlaceholder="Pesquisar complicação..."
                            emptyMessage="Nenhuma complicação encontrada."
                            error={errors.complicacoes}
                        />

                        {complicacoesSelecionadas.length > 0 && (
                            <div className="space-y-3">
                                <span className="text-sm font-medium">
                                    Complicações identificadas ({complicacoesSelecionadas.length})
                                </span>

                                {complicacoesSelecionadas.map((complicacao) => {
                                    const nome = complicacoesOptions.find((option) => String(option.id) === complicacao.id)?.nome ?? complicacao.id;
                                    const semResolucao = tentouSubmeter && complicacao.resolucao_ids.length === 0;

                                    return (
                                        <div
                                            key={complicacao.id}
                                            className={`rounded-md border p-3 ${semResolucao ? 'border-red-400 bg-red-50 dark:border-red-900 dark:bg-red-950/20' : 'border-dashed border-neutral-300 dark:border-neutral-700'}`}
                                        >
                                            <div className="mb-2 flex items-center justify-between gap-2">
                                                <span className="text-sm font-medium">{nome}</span>
                                                <button
                                                    type="button"
                                                    onClick={() => handleRemoverComplicacao(complicacao.id)}
                                                    className="text-neutral-400 hover:text-red-600 dark:hover:text-red-400"
                                                    aria-label={`Remover ${nome}`}
                                                    title="Remover complicação"
                                                >
                                                    <X className="size-4" />
                                                </button>
                                            </div>

                                            <AppMultiSelect
                                                value={complicacao.resolucao_ids}
                                                onChange={(value) => handleResolucaoIdsChange(complicacao.id, value)}
                                                options={resolucoesComplicacaoOptions.map((option) => ({
                                                    value: String(option.id),
                                                    label: option.nome,
                                                }))}
                                                placeholder="Selecionar resolução..."
                                                searchPlaceholder="Pesquisar resolução..."
                                                emptyMessage="Nenhuma resolução encontrada."
                                                error={semResolucao ? 'Escolha pelo menos uma resolução para esta complicação.' : undefined}
                                            />
                                        </div>
                                    );
                                })}
                            </div>
                        )}

                        {complicacoesInvalidas && (
                            <p className="text-sm text-red-600 dark:text-red-400">
                                Há complicações sem resolução escolhida. Complete-as para poder guardar.
                            </p>
                        )}
                    </div>
                </div>
            )}
            <div className="mt-6">
                <label htmlFor="comentarios" className="mb-2 block text-sm font-medium">
                    Comentários
                </label>

                <textarea
                    id="comentarios"
                    value={form.comentarios ?? ''}
                    onChange={(event) => updateField('comentarios', event.target.value)}
                    rows={4}
                    className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                    placeholder="Comentários adicionais"
                />

                {errors.comentarios && <p className="mt-1 text-sm text-red-600">{errors.comentarios}</p>}
            </div>
        </AppModalForm>
    );
}
