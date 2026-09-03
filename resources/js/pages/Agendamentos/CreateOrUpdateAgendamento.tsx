import { AppInputField } from '@/components/app/app-input-field';
import { AppSelectField } from '@/components/app/app-input-select';
import { Option, User } from '@/types/type';
import { AppModalForm } from '@/components/app/app-modal-form';
import { useCrudForm } from '@/hooks/use-crud-form';
import { AgendamentoItem } from '@/types/type';
import { useEffect } from 'react';

type Props = {
    agendamento?: AgendamentoItem | null;
    isOpenAgendamento: boolean;
    listaDeEsperaId?: number;
    estadoDeAgendamentoOptions: Option[];
    responsavelOptions: Option[];
    tipoDeAgendamentoOptions: Option[];
    localDeAgendamentoOptions: Option[];
    salaDeAgendamentoOptions: Option[];
    
    onClose: () => void;
    onSuccess: () => void;
};

const emptyForm = (listaDeEsperaId?: number): AgendamentoItem => ({
    id: 0,
    lista_de_espera_id: listaDeEsperaId ?? 0,
    start: '',
    end: '',
    responsavel_id: null,
    tipo_de_agendamento_id: 0,
    local_de_agendamento_id: 0,
    sala_de_agendamento_id: 0,
    periodo_de_agendamento_id: 0,
    estado_de_agendamento_id: 0,
    comentarios: null,
});

export default function CreateOrUpdateAgendamento({ agendamento, isOpenAgendamento, listaDeEsperaId, onClose, onSuccess, responsavelOptions, tipoDeAgendamentoOptions, localDeAgendamentoOptions, estadoDeAgendamentoOptions, salaDeAgendamentoOptions }: Props) {
    const isEditing = Boolean(agendamento?.id);

    const { form, errors, loading, updateField, resetForm, submit } = useCrudForm<AgendamentoItem>(
        {
            ...emptyForm(listaDeEsperaId),
            ...agendamento,
        },
        {
            url: isEditing && agendamento ? route('agendamentos.update', agendamento.id) : route('agendamentos.store'),

            isEditing,

            successMessage: isEditing ? 'Agendamento atualizado com sucesso.' : 'Agendamento criado com sucesso.',

            onSuccess,
        },
    );

    useEffect(() => {
        resetForm({
            ...emptyForm(listaDeEsperaId),
            ...agendamento,
            lista_de_espera_id: agendamento?.lista_de_espera_id ?? listaDeEsperaId ?? 0,
        });
    }, [agendamento, listaDeEsperaId, resetForm]);

    return (
        <AppModalForm
            open={isOpenAgendamento}
            title={isEditing ? 'Editar agendamento' : 'Novo agendamento'}
            description={isEditing ? 'Atualize os dados do agendamento.' : 'Preencha os dados para criar um novo agendamento.'}
            onClose={onClose}
            onSubmit={submit}
            loading={loading}
            submitLabel={isEditing ? 'Guardar alterações' : 'Criar agendamento'}
        >
            <div className="space-y-4 grid grid-cols-2 gap-4">
                <AppInputField
                    label="Data"
                    type="datetime-local"
                    value={form.start}
                    onChange={(value) => updateField('start', String(value))}
                    error={errors.start}
                />

                <AppSelectField
                    label="Responsável"
                    value={form.responsavel_id ?? ''}
                    onChange={(value) => updateField('responsavel_id', value === '' ? null : Number(value))}
                    error={errors.responsavel_id}
                    options={responsavelOptions} // Substitua com as opções reais de responsáveis
                />

                <AppSelectField
                    label="Tipo de agendamento"
                    value={form.tipo_de_agendamento_id ?? 0}
                    onChange={(value) => updateField('tipo_de_agendamento_id',Number(value))}
                    error={errors.tipo_de_agendamento_id}
                    options={tipoDeAgendamentoOptions} // Substitua com as opções reais de tipos de agendamento
                />

                <AppSelectField
                    label="Local de agendamento"
                    value={form.local_de_agendamento_id ?? 0}
                    onChange={(value) => updateField('local_de_agendamento_id', Number(value))}
                    error={errors.local_de_agendamento_id}
                    options={localDeAgendamentoOptions} // Substitua com as opções reais de locais de agendamento
                />

                <AppSelectField
                    label="Sala de agendamento"
                    value={form.sala_de_agendamento_id ?? 0}
                    onChange={(value) => updateField('sala_de_agendamento_id', Number(value))}
                    error={errors.sala_de_agendamento_id}
                    options={salaDeAgendamentoOptions} // Substitua com as opções reais de salas de agendamento
                />

                <AppSelectField
                    label="Estado de agendamento"
                    value={form.estado_de_agendamento_id ?? 0}
                    onChange={(value) => updateField('estado_de_agendamento_id', Number(value))}
                    error={errors.estado_de_agendamento_id}
                    options={estadoDeAgendamentoOptions} // Substitua com as opções reais de estados de agendamento
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
