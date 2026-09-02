import { AppInputField } from '@/components/app/app-input-field';
import { AppSelectField } from '@/components/app/app-input-select';
import { AppModalForm } from '@/components/app/app-modal-form';
import { useCrudForm } from '@/hooks/use-crud-form';
import { User } from '@/types/type';
import { useEffect } from 'react';

interface Props {
    open: boolean;
    onClose: () => void;
    user?: User | null;
}

interface FormData extends Record<string, string | boolean> {
    name: string;
    email: string;
    password: string;
    username: string;
    abrev: string;
    ativo: boolean;
    equipa: string;
    sexo: string;
}

const emptyForm: FormData = {
    name: '',
    email: '',
    password: '',
    username: '',
    abrev: '',
    ativo: true,
    equipa: '',
    sexo: '',
};

export default function CreateOrUpdateUserModal({ open, onClose, user }: Props) {
    const isEdit = user !== null && user !== undefined;

    const { form, errors, loading, updateField, resetForm, submit } = useCrudForm<FormData>(emptyForm, {
        url: isEdit ? `/users/${user?.id}` : '/users',
        isEditing: isEdit,
        successMessage: isEdit ? 'Utilizador atualizado com sucesso.' : 'Utilizador criado com sucesso.',
        errorMessage: 'Erro ao guardar o utilizador.',
        preserveScroll: false,
        onSuccess: () => onClose(),
    });

    useEffect(() => {
        if (!open) {
            return;
        }

        if (user) {
            resetForm({
                name: user.name,
                email: user.email,
                password: '',
                username: user.username,
                abrev: user.abrev,
                ativo: user.ativo,
                equipa: user.equipa,
                sexo: user.sexo,
            });
        } else {
            resetForm({ ...emptyForm });
        }
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [open, user]);

    if (!open) {
        return null;
    }

    return (
        <AppModalForm
            open
            title={isEdit ? 'Editar Doente' : 'Novo Doente'}
            description={isEdit ? 'Atualize os dados do doente.' : 'Introduza os dados de identificação do doente.'}
            onClose={onClose}
            onSubmit={submit}
            loading={loading}
            maxWidth="5xl"
            submitLabel={isEdit ? 'Guardar alterações' : 'Criar doente'}
        >
            <div className="grid gap-6 md:grid-cols-2">
                <AppInputField
                    label="Nome"
                    value={form.name}
                    onChange={(value) => updateField('name', value)}
                    error={errors.name}
                    placeholder="Nome completo"
                />

                <AppInputField
                    label="Email"
                    type="email"
                    value={form.email}
                    onChange={(value) => updateField('email', value)}
                    error={errors.email}
                    placeholder="Email"
                />

                <AppInputField
                    label="Password"
                    type="password"
                    value={form.password}
                    onChange={(value) => updateField('password', value)}
                    error={errors.password}
                    placeholder="Password"
                />
                <AppInputField
                    label="Username"
                    value={form.username}
                    onChange={(value) => updateField('username', value)}
                    error={errors.username}
                    placeholder="Username"
                />
                <AppInputField
                    label="Abrev"
                    value={form.abrev}
                    onChange={(value) => updateField('abrev', value)}
                    error={errors.abrev}
                    placeholder="Abrev"
                />
                <AppInputField
                    label="Equipa"
                    value={form.equipa}
                    onChange={(value) => updateField('equipa', value)}
                    error={errors.equipa}
                    placeholder="Equipa"
                />
                <AppSelectField
                    label="Sexo"
                    value={form.sexo}
                    onChange={(value) => updateField('sexo', String(value))}
                    error={errors.sexo}
                    options={[
                        { value: 1, label: 'Masculino' },
                        { value: 2, label: 'Feminino' },
                    ]}
                />
            </div>
        </AppModalForm>
    );
}
