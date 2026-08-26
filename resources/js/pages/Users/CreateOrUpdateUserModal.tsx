import { AppInputField } from '@/components/app/app-input-field';
import { AppSelectField } from '@/components/app/app-input-select';
import { AppModalForm } from '@/components/app/app-modal-form';
import { User } from '@/types/type';
import { router } from '@inertiajs/react';
import { FormEvent, useEffect, useState } from 'react';
import toast from 'react-hot-toast';

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

    const [form, setForm] = useState<FormData>(emptyForm);
    const [loading, setLoading] = useState(false);
    const [errors, setErrors] = useState<Record<string, string>>({});

    useEffect(() => {
        if (!open) {
            return;
        }

        if (user) {
            setForm({
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
            setForm({ ...emptyForm });
        }

        setErrors({});
    }, [open, user]);

    const updateField = <K extends keyof FormData>(field: K, value: FormData[K]) => {
        setForm((current) => ({
            ...current,
            [field]: value,
        }));

        setErrors((current) => ({
            ...current,
            [field]: '',
        }));
    };

    const submit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        setLoading(true);
        setErrors({});

        if (isEdit && !user) {
            setLoading(false);
            return;
        }

        const url = isEdit ? `/users/${user.id}` : '/users';

        const options = {
            onError: (formErrors: Record<string, string>) => {
                setErrors(formErrors);
                toast.error('Erro ao guardar o utilizador.');
            },

            onFinish: () => {
                setLoading(false);
            },

            onSuccess: () => {
                onClose();

                toast.success(isEdit ? 'Utilizador atualizado com sucesso.' : 'Utilizador criado com sucesso.');
            },
        };

        if (isEdit) {
            router.put(url, form, options);
        } else {
            router.post(url, form, options);
        }
    };

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
                    label="Abreviatura"
                    value={form.abrev}
                    onChange={(value) => updateField('abreviatura', value)}
                    error={errors.abreviatura}
                    placeholder="Abreviatura"
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
