import { AppInputField } from '@/components/app/app-input-field';
import { AppSelectField } from '@/components/app/app-input-select';
import { AppModalForm } from '@/components/app/app-modal-form';
import { useCrudForm } from '@/hooks/use-crud-form';
import type { ClavienDindoItem, ResolucaoComplicacaoItem } from '@/types/type';
import { useEffect } from 'react';

interface Props {
    open: boolean;
    onClose: () => void;
    resolucaoComplicacao?: ResolucaoComplicacaoItem | null;
    clavienDindoOptions?: ClavienDindoItem[];
}

interface FormData extends Record<string, string | number | null> {
    nome: string;
    clavien_dindo_id: number | null;
}

const emptyForm: FormData = {
    nome: '',
    clavien_dindo_id: null,
};

export default function CreateOrUpdateResolucaoComplicacaoModal({ open, onClose, resolucaoComplicacao, clavienDindoOptions = [] }: Props) {
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

        resetForm(
            resolucaoComplicacao
                ? { nome: resolucaoComplicacao.nome, clavien_dindo_id: resolucaoComplicacao.clavien_dindo_id ?? null }
                : { ...emptyForm },
        );
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
                placeholder="Ex.: Antibioterapia, Intervenção Cirurgica..."
            />

            <AppSelectField
                label="Grau Clavien-Dindo associado"
                value={form.clavien_dindo_id ?? ''}
                onChange={(value) => updateField('clavien_dindo_id', value === '' ? null : Number(value))}
                error={errors.clavien_dindo_id}
                options={clavienDindoOptions.map((option) => ({
                    value: option.id,
                    label: option.nome,
                }))}
            />
            <p className="text-muted-foreground -mt-2 text-xs">
                Opcional. Usado para sugerir a classificação Clavien-Dindo no formulário de internamento quando esta resolução é escolhida.
            </p>
        </AppModalForm>
    );
}
