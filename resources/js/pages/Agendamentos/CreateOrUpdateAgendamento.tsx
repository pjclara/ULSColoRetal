import { AppInputField } from '@/components/app/app-input-field';
import { AppSelectField } from '@/components/app/app-input-select';
import { Option, User } from '@/types/type';
import { AppModalForm } from '@/components/app/app-modal-form';
import { useCrudForm } from '@/hooks/use-crud-form';
import { AgendamentoItem } from '@/types/type';
import { useEffect } from 'react';

const normalizeSelectOptions = (options: Option[]) =>
    options.map((option) => ({
        ...option,
        value: String(option.value),
    }));

type Props = {
    agendamento?: Partial<AgendamentoItem> | null;
    isOpenAgendamento: boolean;
    listaDeEsperaId?: number;
    estadoDeAgendamentoOptions: Option[];
    responsavelOptions: Option[];
    tipoDeAgendamentoOptions: Option[];
    localDeAgendamentoOptions: Option[];
    salaDeAgendamentoOptions: Option[];
    /** Quando fornecido, mostra um seletor de Lista de Espera/Utente (ex: ao criar a partir do calendário). */
    listaDeEsperaOptions?: Option[];
    /** Mostra um botão "Remover" no rodapé (ex: ao editar um agendamento a partir do calendário). */
    onDelete?: () => void;
    deleting?: boolean;

    onClose: () => void;
    onSuccess: (agendamento?: AgendamentoItem) => void;
};

/** Nova marcação começa sempre "Agendado" (ver EstadoDeAgendamento::optionsParaAgendamento). */
const estadoAgendadoId = (estadoDeAgendamentoOptions: Option[]): number => {
    const option = estadoDeAgendamentoOptions.find((o) => o.label.trim().toLowerCase() === 'agendado');
    return option ? Number(option.value) : 0;
};

const emptyForm = (listaDeEsperaId: number | undefined, estadoDeAgendamentoOptions: Option[]): AgendamentoItem => ({
    id: 0,
    lista_de_espera_id: listaDeEsperaId ?? 0,
    start: '',
    end: '',
    responsavel_id: null,
    tipo_de_agendamento_id: 0,
    local_de_agendamento_id: 0,
    sala_de_agendamento_id: 0,
    periodo_de_agendamento_id: null,
    estado_de_agendamento_id: estadoAgendadoId(estadoDeAgendamentoOptions),
    comentarios: null,
});

export default function CreateOrUpdateAgendamento({ agendamento, isOpenAgendamento, listaDeEsperaId, onClose, onSuccess, responsavelOptions, tipoDeAgendamentoOptions, localDeAgendamentoOptions, estadoDeAgendamentoOptions, salaDeAgendamentoOptions, listaDeEsperaOptions, onDelete, deleting }: Props) {
    const isEditing = Boolean(agendamento?.id);

    const { form, errors, loading, updateField, resetForm, submit } = useCrudForm<AgendamentoItem>(
        {
            ...emptyForm(listaDeEsperaId, estadoDeAgendamentoOptions),
            ...agendamento,
        },
        {
            url: isEditing && agendamento ? route('agendamentos.update', agendamento.id) : route('agendamentos.store'),

            isEditing,

            successMessage: isEditing ? 'Agendamento atualizado com sucesso.' : 'Agendamento criado com sucesso.',

            onSuccess: (page) => {
                const created = (page.props as { flash?: { agendamento?: AgendamentoItem } }).flash?.agendamento;
                onSuccess(created);
            },
        },
    );

    useEffect(() => {
        resetForm({
            ...emptyForm(listaDeEsperaId, estadoDeAgendamentoOptions),
            ...agendamento,
            lista_de_espera_id: agendamento?.lista_de_espera_id ?? listaDeEsperaId ?? 0,
        });
    }, [agendamento, listaDeEsperaId, estadoDeAgendamentoOptions, resetForm]);

    return (
        <AppModalForm
            open={isOpenAgendamento}
            title={isEditing ? 'Editar agendamento' : 'Novo agendamento'}
            description={isEditing ? 'Atualize os dados do agendamento.' : 'Preencha os dados para criar um novo agendamento.'}
            onClose={onClose}
            onSubmit={submit}
            loading={loading}
            submitLabel={isEditing ? 'Guardar alterações' : 'Criar agendamento'}
            footerStart={
                isEditing && onDelete ? (
                    <button
                        type="button"
                        onClick={onDelete}
                        disabled={deleting}
                        className="text-sm font-medium text-red-600 hover:text-red-700 disabled:opacity-50 dark:text-red-400 dark:hover:text-red-300"
                    >
                        {deleting ? 'A remover...' : 'Remover agendamento'}
                    </button>
                ) : undefined
            }
        >
            <div className="space-y-4 grid grid-cols-2 gap-4">
                {listaDeEsperaOptions && (
                    <div className="col-span-2">
                        <AppSelectField
                            label="Utente / Lista de espera"
                            value={String(form.lista_de_espera_id || '')}
                            onChange={(value) => updateField('lista_de_espera_id', Number(value))}
                            error={errors.lista_de_espera_id}
                            options={normalizeSelectOptions(listaDeEsperaOptions)}
                        />
                    </div>
                )}

                {/* Um agendamento é um ponto no tempo (o "fim" acompanha sempre o início — ver AgendamentoService), por isso não há um campo separado para a data de fim. */}
                <AppInputField
                    label="Data e hora"
                    type="datetime-local"
                    value={form.start}
                    onChange={(value) => {
                        const start = String(value);
                        updateField('start', start);
                        updateField('end', start);
                    }}
                    error={errors.start}
                />

                <AppSelectField
                    label="Responsável"
                    value={String(form.responsavel_id ?? '')}
                    onChange={(value) => updateField('responsavel_id', value === '' ? null : Number(value))}
                    error={errors.responsavel_id}
                    options={normalizeSelectOptions(responsavelOptions)} // Substitua com as opções reais de responsáveis
                />

                <AppSelectField
                    label="Tipo de agendamento"
                    value={String(form.tipo_de_agendamento_id ?? 0)}
                    onChange={(value) => updateField('tipo_de_agendamento_id',Number(value))}
                    error={errors.tipo_de_agendamento_id}
                    options={normalizeSelectOptions(tipoDeAgendamentoOptions)} // Substitua com as opções reais de tipos de agendamento
                />

                <AppSelectField
                    label="Local de agendamento"
                    value={String(form.local_de_agendamento_id ?? 0)}
                    onChange={(value) => updateField('local_de_agendamento_id', Number(value))}
                    error={errors.local_de_agendamento_id}
                    options={normalizeSelectOptions(localDeAgendamentoOptions)} // Substitua com as opções reais de locais de agendamento
                />

                <AppSelectField
                    label="Sala de agendamento"
                    value={String(form.sala_de_agendamento_id ?? 0)}
                    onChange={(value) => updateField('sala_de_agendamento_id', Number(value))}
                    error={errors.sala_de_agendamento_id}
                    options={normalizeSelectOptions(salaDeAgendamentoOptions)} // Substitua com as opções reais de salas de agendamento
                />

                <AppSelectField
                    label="Estado de agendamento"
                    value={String(form.estado_de_agendamento_id ?? 0)}
                    onChange={(value) => updateField('estado_de_agendamento_id', Number(value))}
                    error={errors.estado_de_agendamento_id}
                    options={normalizeSelectOptions(estadoDeAgendamentoOptions)}
                />

                <AppInputField
                    label="Comentários"
                    value={form.comentarios ?? ''}
                    onChange={(value) => updateField('comentarios', value === '' ? null : String(value))}
                    error={errors.comentarios}
                    placeholder="Descreva detalhes do agendamento..."
                />
            </div>
        </AppModalForm>
    );
}
