import { AppCheckboxField } from '@/components/app/app-check-box-field';
import { AppInputField } from '@/components/app/app-input-field';
import { AppSelectField } from '@/components/app/app-input-select';
import { AppModalForm } from '@/components/app/app-modal-form';
import { useCrudForm } from '@/hooks/use-crud-form';
import { ListaDeEsperaItem, Option } from '@/types/type';
import { useEffect } from 'react';

type Props = {
    listaDeEspera?: ListaDeEsperaItem;
    isOpenListaDeEspera: boolean;
    utenteId: number | null;
    onClose: () => void;
    onSuccess: () => void;
    estadoOptions: Option[];
    responsavelOptions: Option[];
};

const emptyForm = (utenteId: number | null): ListaDeEsperaItem => ({
    id: 0,
    utente_id: utenteId ?? 0,
    data_de_lista: '',
    estado_lista_espera: '',
    cancelar_lista_espera: false,
    comentarios: null,
    responsavel_id: null,
});

export default function CreateOrUpdateListaDeEspera({
    listaDeEspera,
    isOpenListaDeEspera,
    utenteId,
    onClose,
    onSuccess,
    estadoOptions,
    responsavelOptions,
}: Props) {
    const isEditing = Boolean(listaDeEspera?.id);

    const {
        form,
        errors,
        loading,
        updateField,
        resetForm,
        submit,
    } = useCrudForm<ListaDeEsperaItem>(
        {
            ...emptyForm(utenteId),
            ...listaDeEspera,
        },
        {
            url:
                isEditing && listaDeEspera
                    ? route(
                          'lista-de-esperas.update',
                          listaDeEspera.id,
                      )
                    : route('lista-de-esperas.store'),

            isEditing,

            successMessage: isEditing
                ? 'Lista de espera atualizada com sucesso.'
                : 'Lista de espera criada com sucesso.',

            onSuccess: onSuccess,
        },
    );

    useEffect(() => {
        resetForm({
            ...emptyForm(utenteId),
            ...listaDeEspera,
        });
    }, [listaDeEspera, resetForm, utenteId]);

    return (
        <AppModalForm
            open={isOpenListaDeEspera}
            title={
                isEditing
                    ? 'Editar Lista de Espera'
                    : 'Criar Lista de Espera'
            }
            description={
                isEditing
                    ? 'Atualize os dados da lista de espera.'
                    : 'Introduza os dados da nova lista de espera.'
            }
            onClose={onClose}
            onSubmit={submit}
            loading={loading}
            submitLabel={
                isEditing
                    ? 'Guardar alterações'
                    : 'Criar lista de espera'
            }
        >
            <AppInputField
                label="Data de entrada em lista"
                type="date"
                value={form.data_de_lista}
                onChange={(value) => updateField('data_de_lista', String(value))}
                error={errors.data_de_lista}
            />

            <AppSelectField
                label="Estado da Lista de Espera"
                value={form.estado_lista_espera}
                onChange={(value) =>
                    updateField(
                        'estado_lista_espera',
                        String(value),
                    )
                }
                error={errors.estado_lista_espera}
                options={estadoOptions}
            />

            <AppCheckboxField
                label="Cancelar Lista de Espera"
                checked={form.cancelar_lista_espera}
                onChange={(value) =>
                    updateField(
                        'cancelar_lista_espera',
                        Boolean(value),
                    )
                }
                error={errors.cancelar_lista_espera}
            />

            <AppInputField
                label="Comentários"
                value={form.comentarios ?? ''}
                onChange={(value) =>
                    updateField(
                        'comentarios',
                        value === '' ? null : String(value),
                    )
                }
                error={errors.comentarios}
            />

            <AppSelectField
                label="Responsável"
                value={form.responsavel_id ?? ''}
                onChange={(value) =>
                    updateField(
                        'responsavel_id',
                        value === '' ? null : Number(value),
                    )
                }
                error={errors.responsavel_id}
                options={responsavelOptions}
            />
        </AppModalForm>
    );
}
