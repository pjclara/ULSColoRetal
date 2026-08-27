import { AppEntitySummary } from '@/components/app/app-entity-summary';
import { Button } from '@/components/ui/button';

import type { CentroDeReferenciaItem, UtenteItem } from '@/types/type';

import { router } from '@inertiajs/react';
import { FormEvent, useEffect, useState } from 'react';
import toast from 'react-hot-toast';

type Props = {
    utente: UtenteItem;
    centroDeReferencia?: CentroDeReferenciaItem | null;
    onBack: () => void;
    onContinue: () => void;
    onSuccess?: (centro: CentroDeReferenciaItem) => void;
};

type FormData = {
    utente_id: number;
    data_de_diagnostico: string;
    data_de_referenciacao: string;
    origem_id: number | string;
    data_de_entrada: string;
    data_de_saida: string;
    destino_id: number | string;
    responsavel_id: number | string;
    comentarios: string;
};

const emptyForm: FormData = {
    utente_id: 0,
    data_de_diagnostico: '',
    data_de_referenciacao: '',
    origem_id: '',
    data_de_entrada: '',
    data_de_saida: '',
    destino_id: '',
    responsavel_id: '',
    comentarios: '',
};

export default function CreateOrUpdateCentroDeReferencia({ utente, centroDeReferencia, onBack, onContinue, onSuccess }: Props) {
    const editing = !!centroDeReferencia;

    const [form, setForm] = useState<FormData>(emptyForm);
    const [errors, setErrors] = useState<Record<string, string>>({});
    const [loading, setLoading] = useState(false);

    useEffect(() => {
        if (centroDeReferencia) {
            setForm({
                utente_id: centroDeReferencia.utente_id,
                data_de_diagnostico: centroDeReferencia.data_de_diagnostico ?? '',
                data_de_referenciacao: centroDeReferencia.data_de_referenciacao ?? '',
                origem_id: centroDeReferencia.origem_id ?? '',
                data_de_entrada: centroDeReferencia.data_de_entrada ?? '',
                data_de_saida: centroDeReferencia.data_de_saida ?? '',
                destino_id: centroDeReferencia.destino_id ?? '',
                responsavel_id: centroDeReferencia.responsavel_id ?? '',
                comentarios: centroDeReferencia.comentarios ?? '',
            });
        } else {
            setForm({
                ...emptyForm,
                utente_id: utente.id,
            });
        }

        setErrors({});
    }, [open, centroDeReferencia, utente]);

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

        const options = {
            preserveScroll: true,

            onSuccess: (page: any) => {
                toast.success(editing ? 'Centro de referência atualizado com sucesso.' : 'Centro de referência criado com sucesso.');

                const created = page.props.flash?.centro_de_referencia;

                if (created) {
                    onSuccess?.(created);
                }
            },

            onError: (validationErrors: Record<string, string>) => {
                setErrors(validationErrors);

                toast.error('Verifique os dados introduzidos.');
            },

            onFinish: () => {
                setLoading(false);
            },
        };

        if (editing) {
            router.put(`/centros-de-referencia/${centroDeReferencia!.id}`, form, options);
        } else {
            router.post('/centros-de-referencia', form, options);
        }
    };

    return (
        <div className="space-y-6">
            <div>
                <h2 className="text-xl font-semibold">Selecionar internamento</h2>

                <p className="mt-1 text-sm text-neutral-500">Selecione um internamento existente para o utente ou crie um novo.</p>
            </div>

            <AppEntitySummary
                title="Utente selecionado"
                fields={[
                    {
                        label: 'Nome',
                        value: utente.nome,
                    },
                    {
                        label: 'N.º Processo',
                        value: utente.numero_processo ?? '',
                    },
                    {
                        label: 'Data de nascimento',
                        value: utente.data_nascimento ?? '',
                    },
                ]}
                action={
                    <Button type="button" variant="outline" onClick={onBack}>
                        Alterar utente
                    </Button>
                }
            />
            {centroDeReferencia ? (
                <AppEntitySummary
                    title="Centro de referência"
                    fields={[
                        {
                            label: 'Referenciação',
                            value: centroDeReferencia.data_de_referenciacao ?? '',
                        },                        
                    ]}
                    action={
                        <Button type="button" variant="outline" onClick={onContinue}>
                            Continuar
                        </Button>
                    }
                />
            ) : (
                <div className="rounded-lg border p-6">
                    <h2 className="text-lg font-semibold">Centro de referência</h2>
                    <p className="text-muted-foreground mt-1 text-sm">
                        Nenhum centro de referência selecionado. Por favor, crie um novo centro de referência para continuar.
                    </p>
                </div>
            )}
        </div>
    );
}
