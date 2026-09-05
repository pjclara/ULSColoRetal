import { AppInputField } from '@/components/app/app-input-field';
import { AppSelectField } from '@/components/app/app-input-select';
import { AppModalForm } from '@/components/app/app-modal-form';
import { useCrudForm } from '@/hooks/use-crud-form';
import { UtenteItem } from '@/types/type';
import { useEffect } from 'react';

type CreateOrUpdateUtenteProps = {
    utente?: UtenteItem;
    isOpenUtente: boolean;
    onSubmit?: (utente: UtenteItem) => void;
    onCancel?: () => void;
    onClose: () => void;
};

const emptyUtente: UtenteItem = {
    id: null,
    nome: '',
    numero_utente: null,
    sexo_id: null,
    data_nascimento: null,
    numero_processo: null,
    concelho_id: null,
    centro_de_referencia: null,
};

export default function CreateOrUpdateUtente({ utente, onClose, onSubmit, isOpenUtente: isOpenUtenteProp }: CreateOrUpdateUtenteProps) {
    const isEditing = Boolean(utente?.id);

    const { form, errors, loading, updateField, resetForm, submit } = useCrudForm<UtenteItem>(
        { ...emptyUtente, ...utente },
        {
            url: isEditing && utente ? route('utentes.update', (utente as UtenteItem & { id: number }).id) : route('utentes.store'),
            isEditing,
            successMessage: isEditing ? 'Utente atualizado com sucesso.' : 'Utente criado com sucesso.',
            onSuccess: (page) => {
                const created = (page.props as { flash?: { utente?: UtenteItem } }).flash?.utente;

                if (created) {
                    onSubmit?.(created);
                }

                onClose();
            },
        },
    );

    useEffect(() => {
        resetForm({ ...emptyUtente, ...utente });
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [utente]);

    return (
        <AppModalForm
            open={isOpenUtenteProp}
            title={isEditing ? 'Editar Utente' : 'Criar Utente'}
            description={isEditing ? 'Atualize os dados do utente.' : 'Introduza os dados do novo utente.'}
            onClose={onClose}
            onSubmit={submit}
            loading={loading}
            submitLabel={isEditing ? 'Guardar alterações' : 'Criar utente'}
        >
            <AppInputField label="Nome" value={form.nome} onChange={(value) => updateField('nome', String(value))} />
            <AppInputField
                label="Número de Utente"
                value={form.numero_utente ?? ''}
                onChange={(value) => updateField('numero_utente', value === '' ? null : Number(value))}
            />
            <AppInputField
                label="Número de Processo"
                value={form.numero_processo ?? ''}
                onChange={(value) => updateField('numero_processo', value === '' ? null : Number(value))}
            />
            <AppInputField
                label="Data de Nascimento"
                type="date"
                value={form.data_nascimento ?? ''}
                onChange={(value) => updateField('data_nascimento', String(value))}
            />
            <AppSelectField
                label="Sexo ID"
                value={form.sexo_id ?? ''}
                onChange={(value) => updateField('sexo_id', value === '' ? null : Number(value))}
                options={[
                    { value: '1', label: 'Masculino' },
                    { value: '2', label: 'Feminino' },
                ]}
            />
        </AppModalForm>
    );
}

