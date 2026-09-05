import { AppInputField } from '@/components/app/app-input-field';
import { AppSelectField } from '@/components/app/app-input-select';
import { AppModalForm } from '@/components/app/app-modal-form';
import AppMultiSelect from '@/components/app/app-multi-select';
import { useCrudForm } from '@/hooks/use-crud-form';
import type { InternamentoOptions, LookupOption } from '@/types/internamento';
import type { InternamentoItem } from '@/types/type';
import { useEffect } from 'react';

interface Props {
    open: boolean;
    onClose: () => void;
    utenteId: number | null;
    internamento?: InternamentoItem | null;
    internamentoOptions: InternamentoOptions;
    complicacoesOptions?: LookupOption[];
    onSubmit?: () => void;
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
    complicacao_ids: [],
});

export default function CreateOrUpdateInternamentoModal({ open, onClose, utenteId, internamento, internamentoOptions, complicacoesOptions = [], onSubmit }: Props) {
    const isEdit = !!internamento;
    const options = internamentoOptions ?? emptyOptions;

    const { form, errors, loading, updateField, resetForm, submit } = useCrudForm<InternamentoItem>(emptyForm(utenteId), {
        url: isEdit && internamento ? route('internamentos.update', (internamento as InternamentoItem & { id: number }).id) : route('internamentos.store'),
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

                complicacao_ids: internamento.complicacaos?.map((complicacao) => String(complicacao.id)) ?? [],
            });
        } else {
            resetForm(emptyForm(utenteId));
        }
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [open, internamento, utenteId]);

    // O estado da alta é sempre calculado automaticamente (ver InternamentoService::atualizarEstadoDaAlta):
    // operado -> Pendente, não operado -> Concluída. Não é escolhido aqui.
    const operado = (internamento?.bloco_operatorios?.length ?? 0) > 0;
    const estadoAltaLabel = operado ? 'Pendente (doente operado)' : 'Concluída (doente não operado)';

    // Clavien-Dindo só é avaliável depois de passarem 30 dias sobre a alta (morbilidade cirúrgica aos 30 dias).
    const diasDesdeAlta = diasDesde(form.data_de_alta);
    const clavienDindoDisponivel = diasDesdeAlta !== null && diasDesdeAlta >= 30;

    if (!open) {
        return null;
    }

    return (
        <AppModalForm
            open={open}
            title={isEdit ? 'Editar Internamento' : 'Novo Internamento'}
            description={isEdit ? 'Atualize os dados do internamento.' : 'Introduza os dados do novo internamento.'}
            onClose={onClose}
            onSubmit={submit}
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

                    <div>
                        <span className="mb-2 block text-sm font-medium">Estado da alta</span>
                        <div className="border-input bg-muted text-muted-foreground flex h-10 items-center rounded-md border px-3 text-sm">
                            {estadoAltaLabel}
                        </div>
                        <p className="text-muted-foreground mt-1 text-xs">
                            Calculado automaticamente: fica Pendente enquanto houver blocos operatórios associados.
                        </p>
                    </div>

                    {clavienDindoDisponivel ? (
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
                    )}

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

                    <div className="md:col-span-2">
                        <AppMultiSelect
                            label="Complicações"
                            value={form.complicacao_ids ?? []}
                            onChange={(value) => updateField('complicacao_ids', value)}
                            options={complicacoesOptions.map((option) => ({
                                value: String(option.id),
                                label: option.nome,
                            }))}
                            placeholder="Selecionar complicações..."
                            searchPlaceholder="Pesquisar complicação..."
                            emptyMessage="Nenhuma complicação encontrada."
                            error={errors.complicacao_ids}
                        />
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
