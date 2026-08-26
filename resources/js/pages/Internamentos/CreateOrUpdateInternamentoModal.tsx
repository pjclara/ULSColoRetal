import { AppInputField } from '@/components/app/app-input-field';
import { AppSelectField } from '@/components/app/app-input-select';
import { AppModalForm } from '@/components/app/app-modal-form';
import type { InternamentoOptions } from '@/types/internamento';
import type { InternamentoItem } from '@/types/type';
import { router } from '@inertiajs/react';
import { FormEvent, useEffect, useState } from 'react';
import toast from 'react-hot-toast';

interface Props {
    open: boolean;
    onClose: () => void;
    utenteId: number | null;
    internamento?: InternamentoItem | null;
    internamentoOptions: InternamentoOptions;
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
});

export default function CreateOrUpdateInternamentoModal({ open, onClose, utenteId, internamento, internamentoOptions }: Props) {
    const isEdit = !!internamento;
    const options = internamentoOptions ?? emptyOptions;

    const [form, setForm] = useState<InternamentoItem>(emptyForm(utenteId));

    const [loading, setLoading] = useState(false);
    const [errors, setErrors] = useState<Record<string, string>>({});

    useEffect(() => {
        if (!open) {
            return;
        }

        if (internamento) {
            setForm({
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
            });
        } else {
            setForm(emptyForm(utenteId));
        }

        setErrors({});
    }, [open, internamento, utenteId]);

    const updateField = <K extends keyof InternamentoItem>(field: K, value: InternamentoItem[K]) => {
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

        const options = {
            preserveScroll: true,

            onError: (formErrors: Record<string, string>) => {
                setErrors(formErrors);
                toast.error('Verifique os dados introduzidos.');
            },

            onFinish: () => {
                setLoading(false);
            },

            onSuccess: () => {
                toast.success(isEdit ? 'Internamento atualizado com sucesso.' : 'Internamento criado com sucesso.');

                onClose();
            },
        };

        if (isEdit && internamento) {
            router.put(route('internamentos.update', (internamento as InternamentoItem & { id: number }).id), form, options);
        } else {
            router.post(route('internamentos.store'), { ...form, utente_id: utenteId ?? form.utente_id }, options);
        }
    };

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
            <div className="grid gap-6 md:grid-cols-2">
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
                <div className="grid gap-6 md:grid-cols-2">
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

                    <AppSelectField
                        label="Estado da alta"
                        value={form.estado_da_alta_id ?? ''}
                        onChange={(value) => updateField('estado_da_alta_id', value === '' ? null : Number(value))}
                        error={errors.estado_da_alta_id}
                        options={options.estadosAlta.map((option) => ({
                            value: option.id,
                            label: option.nome,
                        }))}
                    />

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
