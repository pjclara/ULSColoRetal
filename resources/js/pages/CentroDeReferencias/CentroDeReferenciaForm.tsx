import { AppInputField } from '@/components/app/app-input-field';
import { Button } from '@/components/ui/button';
import { useCrudForm } from '@/hooks/use-crud-form';
import { useEffect } from 'react';

import { AppSelectField } from '@/components/app/app-input-select';
import type { CentroDeReferenciaItem, DestinoItem, OrigemDoInternamentoItem, User, UtenteItem } from '@/types/type';

type Props = {
    utente: UtenteItem;
    centroDeReferencia?: CentroDeReferenciaItem | null;
    origens: OrigemDoInternamentoItem[];
    destinos: DestinoItem[];
    responsaveis: User[];
    onBack: () => void;
    onSuccess?: (centro: CentroDeReferenciaItem) => void;
    onClose: () => void;
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

export default function CentroDeReferenciaForm({ utente, centroDeReferencia, origens, destinos, responsaveis, onClose, onBack, onSuccess }: Props) {
    const editing = !!centroDeReferencia?.utente_id;

    const { form, errors, loading, updateField, resetForm, submit } = useCrudForm<FormData>(emptyForm, {
        url: editing ? `/centro-de-referencias/${centroDeReferencia?.id}` : '/centro-de-referencias',
        isEditing: editing,
        successMessage: editing ? 'Centro de referência atualizado com sucesso.' : 'Centro de referência criado com sucesso.',
        onSuccess: (page) => {
            const created = (page.props as { flash?: { centro_de_referencia?: CentroDeReferenciaItem } }).flash?.centro_de_referencia;

            if (created) {
                onSuccess?.(created);
            }

            onClose();
        },
    });

    // Inicializar formulário
    useEffect(() => {
        if (editing && centroDeReferencia) {
            resetForm({
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
            resetForm({
                ...emptyForm,
                utente_id: utente.id ?? 0,
            });
        }
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [centroDeReferencia, utente]);

    return (
        <form onSubmit={submit} className="space-y-6">
            <div className="flex items-center justify-between">
                <h2 className="text-xl font-semibold">{editing ? 'Editar Centro de Referência' : 'Criar Centro de Referência'}</h2>

                <Button type="button" variant="outline" onClick={onBack}>
                    Voltar
                </Button>
            </div>

            <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
                <AppInputField
                    label="Data de diagnóstico"
                    type="date"
                    value={form.data_de_diagnostico}
                    onChange={(v) => updateField('data_de_diagnostico', String(v))}
                    error={errors.data_de_diagnostico}
                />

                <AppInputField
                    label="Data de referenciação"
                    type="date"
                    value={form.data_de_referenciacao}
                    onChange={(v) => updateField('data_de_referenciacao', String(v))}
                    error={errors.data_de_referenciacao}
                />

                <AppSelectField
                    label="Origem do internamento"
                    value={form.origem_id}
                    onChange={(v) => updateField('origem_id', Number(v))}
                    options={origens.map((o) => ({
                        value: o.id,
                        label: o.nome,
                    }))}
                    error={errors.origem_id}
                />

                <AppInputField
                    label="Data de entrada"
                    type="date"
                    value={form.data_de_entrada}
                    onChange={(v) => updateField('data_de_entrada', String(v))}
                    error={errors.data_de_entrada}
                />

                <AppInputField
                    label="Data de saída"
                    type="date"
                    value={form.data_de_saida}
                    onChange={(v) => updateField('data_de_saida', String(v))}
                    error={errors.data_de_saida}
                />

                <AppSelectField
                    label="Destino"
                    value={form.destino_id}
                    onChange={(v) => updateField('destino_id', Number(v))}
                    options={destinos.map((d) => ({
                        value: d.id,
                        label: d.nome,
                    }))}
                    error={errors.destino_id}
                />

                <AppSelectField
                    label="Responsável"
                    value={form.responsavel_id}
                    onChange={(v) => updateField('responsavel_id', Number(v))}
                    options={responsaveis.map((r) => ({
                        value: r.id,
                        label: r.name,
                    }))}
                    error={errors.responsavel_id}
                />
            </div>

            <AppInputField
                label="Comentários"
                type="text"
                value={form.comentarios}
                onChange={(v) => updateField('comentarios', String(v))}
                error={errors.comentarios}
            />

            <Button type="submit" disabled={loading}>
                {loading ? 'A guardar...' : editing ? 'Atualizar' : 'Criar'}
            </Button>
        </form>
    );
}
