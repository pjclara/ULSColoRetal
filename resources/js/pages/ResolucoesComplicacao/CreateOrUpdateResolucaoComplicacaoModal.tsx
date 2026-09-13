import { AppInputField } from '@/components/app/app-input-field';
import { AppModalForm } from '@/components/app/app-modal-form';
import { useCrudForm } from '@/hooks/use-crud-form';
import type { ResolucaoComplicacaoItem } from '@/types/type';
import { useEffect } from 'react';

interface Props {
    open: boolean;
    onClose: () => void;
    resolucaoComplicacao?: ResolucaoComplicacaoItem | null;
}

interface FormData extends Record<string, string> {
    nome: string;
}

const emptyForm: FormData = {
    nome: '',
};

export default function CreateOrUpdateResolucaoComplicacaoModal({ open, onClose, resolucaoComplicacao }: Props) {
    const isEdit = !!resolucaoComplicacao;

    const { form, errors, loading, updateField, resetForm, submit } = useCrudForm<FormData>(emptyForm, {
        url: isEdit ? route('resolucoes-complicacao.update', resolucaoComplicacao.id) : route('resolucoes-complicacao.store'),
        isEditing: isEdit,
        successMessage: isEdit ? 'Resolução atualizada com sucesso.' : 'Resolução criada com sucesso.',
        errorMessage: 'Erro ao guardar a resolução.',
        onSuccess: () => onClose(),
    });

    useEffect(() => {
        if (!open) {
            return;
        }

        resetForm(resolucaoComplicacao ? { nome: resolucaoComplicacao.nome } : { ...emptyForm });
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [open, resolucaoComplicacao]);

    if (!open) {
        return null;
    }

    return (
        <AppModalForm
            open={open}
            title={isEdit ? 'Editar resolução' : 'Nova resolução'}
            description={isEdit ? 'Atualize o nome da resolução.' : 'Introduza o nome da nova resolução de complicação.'}
            onClose={onClose}
            onSubmit={submit}
            loading={loading}
            maxWidth="md"
            submitLabel={isEdit ? 'Guardar alterações' : 'Criar resolução'}
        >
            <AppInputField
                label="Nome"
                value={form.nome}
                onChange={(value) => updateField('nome', String(value))}
                error={errors.nome}
                placeholder="Ex.: Resolvida, Óbito, Em resolução..."
            />
        </AppModalForm>
    );
}
