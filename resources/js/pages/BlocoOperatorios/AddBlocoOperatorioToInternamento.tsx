import { AppModal } from '@/components/app/app-modal';
import { AppSelectField } from '@/components/app/app-input-select';
import { AppInputField } from '@/components/app/app-input-field';
import AppMultiSelect from '@/components/app/app-multi-select';
import { Button } from '@/components/ui/button';
import type { BlocoOperatorioOptions } from '@/types/internamento';
import type { InternamentoItem } from '@/types/type';
import { router } from '@inertiajs/react';
import { Pencil } from 'lucide-react';
import { useState } from 'react';
import BlocoOperatorioCRSForm from './BlocoOperatorioCRSForm';
import IntervencaoDescricaoForm from './IntervencaoDescricaoForm';

type BlocoOperatorioItem = NonNullable<InternamentoItem['bloco_operatorios']>[number];

type Props = {
    internamento: Pick<InternamentoItem, 'id' | 'bloco_operatorios'>;
    options: BlocoOperatorioOptions;
    onClose: () => void;
    onSave?: () => void;
};

const emptyForm = {
    data_de_inicio: '',
    tipo_de_cirurgia_id: '',
    tipo_de_abordagem_id: '',
    re_intervencao_nao_programada_id: '',
    resseccao_de_orgao: '',
    causa_de_conversao: '',
    comentarios: '',
    intervencao_ids: [] as string[],
};

const RESSECCAO_OPTIONS = [
    { value: '1', label: 'Sim' },
    { value: '2', label: 'Não' },
    { value: '99', label: 'Não aplicável' },
];

export default function AddBlocoOperatorioToInternamento({ internamento, options, onClose, onSave }: Props) {
    const [form, setForm] = useState(emptyForm);
    const [errors, setErrors] = useState<Record<string, string>>({});
    const [loading, setLoading] = useState(false);
    const [editingId, setEditingId] = useState<number | null>(null);

    const blocos = internamento.bloco_operatorios ?? [];
    const isEditing = editingId !== null;
    const editingBloco = isEditing ? blocos.find((b) => b.id === editingId) : undefined;

    const updateField = (field: keyof typeof emptyForm, value: string) => {
        setForm((current) => ({ ...current, [field]: value }));
        setErrors((current) => ({ ...current, [field]: '' }));
    };

    const startEdit = (bloco: BlocoOperatorioItem) => {
        setEditingId(bloco.id);
        setErrors({});
        setForm({
            data_de_inicio: bloco.data_de_inicio ?? '',
            tipo_de_cirurgia_id: bloco.tipo_de_cirurgia_id != null ? String(bloco.tipo_de_cirurgia_id) : '',
            tipo_de_abordagem_id: bloco.tipo_de_abordagem_id != null ? String(bloco.tipo_de_abordagem_id) : '',
            re_intervencao_nao_programada_id: bloco.re_intervencao_nao_programada_id != null ? String(bloco.re_intervencao_nao_programada_id) : '',
            resseccao_de_orgao: bloco.resseccao_de_orgao ?? '',
            causa_de_conversao: bloco.causa_de_conversao ?? '',
            comentarios: bloco.comentarios ?? '',
            intervencao_ids: bloco.intervencoes.map((i) => String(i.intervencao_id)),
        });
    };

    const cancelEdit = () => {
        setEditingId(null);
        setErrors({});
        setForm(emptyForm);
    };

    const refresh = () => (onSave ? onSave() : router.reload({ only: ['internamentos'] }));

    const handleSave = () => {
        setLoading(true);

        const requestOptions = {
            preserveScroll: true,
            onError: (formErrors: Record<string, string>) => setErrors(formErrors),
            onSuccess: () => {
                setForm(emptyForm);
                setEditingId(null);
                refresh();
            },
            onFinish: () => setLoading(false),
        };

        if (isEditing) {
            router.put(route('bloco-operatorios.update', editingId as number), form, requestOptions);
        } else {
            router.post(route('bloco-operatorios.store'), { internamento_id: internamento.id, ...form }, requestOptions);
        }
    };

    const handleRemove = (blocoOperatorioId: number) => {
        router.delete(route('bloco-operatorios.destroy', blocoOperatorioId), {
            preserveScroll: true,
            onSuccess: () => {
                if (editingId === blocoOperatorioId) {
                    cancelEdit();
                }
                refresh();
            },
        });
    };

    // intervenções desta cirurgia que são de centro de referência + resseção: exigem os formulários extra.
    const intervencoesCDR = editingBloco?.intervencoes.filter((i) => i.centro_de_referencia && i.cirurgia_de_ressecao) ?? [];

    return (
        <AppModal title="Bloco Operatório" description="Registe as cirurgias associadas a este internamento." open onClose={onClose} maxWidth="5xl">
            <div className="space-y-6">
                <div className="rounded-xl border bg-neutral-50 p-4 dark:bg-neutral-900">
                    <h2 className="mb-3 text-sm text-neutral-500">{isEditing ? 'Editar cirurgia' : 'Nova cirurgia'}</h2>

                    <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <AppInputField
                            label="Data"
                            type="date"
                            value={form.data_de_inicio}
                            onChange={(value) => updateField('data_de_inicio', String(value))}
                            error={errors.data_de_inicio}
                        />
                        <AppSelectField
                            label="Tipo de cirurgia"
                            value={form.tipo_de_cirurgia_id}
                            onChange={(value) => updateField('tipo_de_cirurgia_id', String(value))}
                            error={errors.tipo_de_cirurgia_id}
                            options={options.tiposDeCirurgia.map((option) => ({ value: String(option.id), label: option.nome }))}
                        />
                        <AppSelectField
                            label="Tipo de abordagem"
                            value={form.tipo_de_abordagem_id}
                            onChange={(value) => updateField('tipo_de_abordagem_id', String(value))}
                            error={errors.tipo_de_abordagem_id}
                            options={options.tiposDeAbordagem.map((option) => ({ value: String(option.id), label: option.nome }))}
                        />
                        <AppSelectField
                            label="Re-intervenção não programada"
                            value={form.re_intervencao_nao_programada_id}
                            onChange={(value) => updateField('re_intervencao_nao_programada_id', String(value))}
                            error={errors.re_intervencao_nao_programada_id}
                            options={options.reIntervencoesNaoProgramadas.map((option) => ({ value: String(option.id), label: option.nome }))}
                        />
                        <AppSelectField
                            label="Resseção de órgão"
                            value={form.resseccao_de_orgao}
                            onChange={(value) => updateField('resseccao_de_orgao', value)}
                            error={errors.resseccao_de_orgao}
                            options={RESSECCAO_OPTIONS}
                        />
                        <AppInputField
                            label="Causa de conversão"
                            value={form.causa_de_conversao}
                            onChange={(value) => updateField('causa_de_conversao', String(value))}
                            error={errors.causa_de_conversao}
                        />
                        <div className="sm:col-span-2">
                            <AppMultiSelect
                                label="Intervenções realizadas"
                                value={form.intervencao_ids}
                                onChange={(value) => setForm((current) => ({ ...current, intervencao_ids: value }))}
                                options={options.intervencoes.map((option) => ({ value: String(option.id), label: option.nome }))}
                                placeholder="Selecionar intervenções..."
                                searchPlaceholder="Pesquisar intervenção..."
                                emptyMessage="Nenhuma intervenção encontrada."
                                error={errors.intervencao_ids}
                            />
                        </div>
                        <div className="sm:col-span-2">
                            <AppInputField
                                label="Comentários"
                                value={form.comentarios}
                                onChange={(value) => updateField('comentarios', String(value))}
                                error={errors.comentarios}
                            />
                        </div>
                    </div>

                    <div className="mt-4 flex justify-end gap-2">
                        {isEditing && (
                            <Button type="button" variant="outline" disabled={loading} onClick={cancelEdit}>
                                Cancelar
                            </Button>
                        )}
                        <Button type="button" disabled={loading} onClick={handleSave}>
                            {loading ? 'A guardar...' : isEditing ? 'Guardar alterações' : 'Adicionar'}
                        </Button>
                    </div>
                </div>

                {/* Dados extra de centro de referência: só quando alguma intervenção guardada exige */}
                {isEditing && editingBloco && intervencoesCDR.length > 0 && (
                    <>
                        <BlocoOperatorioCRSForm
                            blocoOperatorioId={editingBloco.id}
                            existing={editingBloco.bloco_operatorio_c_r_s}
                            options={options.crs}
                            onSaved={refresh}
                        />

                        {intervencoesCDR.map((intervencao) => (
                            <IntervencaoDescricaoForm
                                key={intervencao.pivot_id}
                                pivotId={intervencao.pivot_id}
                                nome={intervencao.nome}
                                existing={intervencao.descricao}
                                options={options.descricao}
                                onSaved={refresh}
                            />
                        ))}
                    </>
                )}

                <div className="rounded-xl border bg-neutral-50 p-4 dark:bg-neutral-900">
                    <h2 className="mb-3 text-sm text-neutral-500">Cirurgias registadas</h2>

                    {blocos.length > 0 ? (
                        <ul className="space-y-2">
                            {blocos.map((bloco) => (
                                <li
                                    key={bloco.id}
                                    className={`flex items-center justify-between rounded-lg border bg-white p-3 dark:bg-neutral-800 ${
                                        editingId === bloco.id ? 'border-primary ring-primary/30 ring-2' : ''
                                    }`}
                                >
                                    <div>
                                        <span className="font-medium">{bloco.data_de_inicio ?? '—'}</span>
                                        {bloco.tipo_de_cirurgia && <span className="ml-2 text-neutral-500">({bloco.tipo_de_cirurgia})</span>}
                                        {bloco.intervencoes.length > 0 && (
                                            <span className="ml-2 text-xs text-neutral-400">
                                                {bloco.intervencoes.map((i) => i.nome).join(', ')}
                                            </span>
                                        )}
                                    </div>

                                    <div className="flex items-center gap-2">
                                        <Button type="button" variant="ghost" size="sm" onClick={() => startEdit(bloco)}>
                                            <Pencil className="size-3.5" />
                                            Editar
                                        </Button>
                                        <Button variant="destructive" size="sm" onClick={() => handleRemove(bloco.id)}>
                                            Remover
                                        </Button>
                                    </div>
                                </li>
                            ))}
                        </ul>
                    ) : (
                        <p className="text-neutral-500">Nenhuma cirurgia registada.</p>
                    )}
                </div>
            </div>
        </AppModal>
    );
}
