import { AppCheckboxField } from '@/components/app/app-check-box-field';
import { AppInputField } from '@/components/app/app-input-field';
import { AppSelectField } from '@/components/app/app-input-select';
import { AppModalForm } from '@/components/app/app-modal-form';
import AppMultiSelect from '@/components/app/app-multi-select';
import { useCrudForm } from '@/hooks/use-crud-form';
import { ListaDeEsperaItem, Option } from '@/types/type';
import { useEffect } from 'react';

type Props = {
    listaDeEspera?: ListaDeEsperaItem | null;
    isOpenListaDeEspera: boolean;
    utenteId?: number | null;
    onClose: () => void;
    onSuccess: () => void;
    estadoOptions: Option[];
    responsavelOptions: Option[];
    diagnosticosOptions: Option[];
};

const emptyForm = (utenteId?: number | null): ListaDeEsperaItem => ({
    id: 0,
    utente_id: utenteId ?? 0,
    data_de_lista: '',
    estado_lista_espera: '',
    cancelar_lista_espera: false,
    comentarios: null,
    responsavel_id: null,
    diagnostico_ids: [],
});

export default function CreateOrUpdateListaDeEspera({
    listaDeEspera,
    isOpenListaDeEspera,
    utenteId,
    onClose,
    onSuccess,
    estadoOptions,
    responsavelOptions,
    diagnosticosOptions,
}: Props) {
    const isEditing = Boolean(listaDeEspera?.id);

    // O estado não é editável aqui: ao inscrever fica sempre "Pendente" e só muda depois por
    // reflexo do estado do agendamento associado (ver AgendamentoService::syncEstadoListaDeEspera).
    const estadoLabel = estadoOptions.find((option) => String(option.value) === String(listaDeEspera?.estado_lista_espera))?.label;

    const { form, errors, loading, updateField, resetForm, submit } = useCrudForm<ListaDeEsperaItem>(
        {
            ...emptyForm(utenteId),
            ...listaDeEspera,
        },
        {
            url: isEditing && listaDeEspera ? route('lista-de-esperas.update', listaDeEspera.id) : route('lista-de-esperas.store'),

            isEditing,

            successMessage: isEditing ? 'Lista de espera atualizada com sucesso.' : 'Lista de espera criada com sucesso.',

            onSuccess: onSuccess,
        },
    );

    useEffect(() => {
        resetForm({
            ...emptyForm(utenteId),
            ...listaDeEspera,
            diagnostico_ids: listaDeEspera?.diagnosticos?.map((diagnostico) => String(diagnostico.id)) ?? [],
        });
    }, [listaDeEspera, resetForm, utenteId]);

    return (
        <AppModalForm
            open={isOpenListaDeEspera}
            title={isEditing ? 'Editar Lista de Espera' : 'Criar Lista de Espera'}
            description={isEditing ? 'Atualize os dados da lista de espera.' : 'Introduza os dados da nova lista de espera.'}
            onClose={onClose}
            onSubmit={submit}
            loading={loading}
            submitLabel={isEditing ? 'Guardar alterações' : 'Criar lista de espera'}
        >
            <AppInputField
                label="Data de entrada em lista"
                type="date"
                value={form.data_de_lista}
                onChange={(value) => updateField('data_de_lista', String(value))}
                error={errors.data_de_lista}
            />
            {isEditing && (
                <div className="text-sm text-neutral-600 dark:text-neutral-400">
                    Estado da Lista de Espera: <span className="font-medium text-neutral-900 dark:text-neutral-100">{estadoLabel ?? '—'}</span>
                    <p className="text-xs text-neutral-400">Só muda automaticamente conforme o estado do agendamento associado.</p>
                </div>
            )}
            {isEditing && (
                <AppCheckboxField
                    label="Cancelar Lista de Espera"
                    checked={form.cancelar_lista_espera}
                    onChange={(value) => updateField('cancelar_lista_espera', Boolean(value))}
                    error={errors.cancelar_lista_espera}
                />
            )}
            <AppMultiSelect
                label="Diagnósticos"
                value={form.diagnostico_ids ?? []}
                onChange={(value) => updateField('diagnostico_ids', value)}
                options={diagnosticosOptions}
                placeholder="Selecionar diagnósticos..."
                searchPlaceholder="Pesquisar diagnóstico..."
                emptyMessage="Nenhum diagnóstico encontrado."
                error={errors.diagnostico_ids}
            />

            <AppSelectField
                label="Responsável"
                value={form.responsavel_id != null ? String(form.responsavel_id) : ''}
                onChange={(value) => updateField('responsavel_id', value === '' ? null : Number(value))}
                error={errors.responsavel_id}
                options={responsavelOptions.map((option) => ({
                    value: String(option.value),
                    label: option.label,
                }))}
            />
            <AppInputField
                label="Comentários"
                value={form.comentarios ?? ''}
                onChange={(value) => updateField('comentarios', value === '' ? null : String(value))}
                error={errors.comentarios}
            />
        </AppModalForm>
    );
}
