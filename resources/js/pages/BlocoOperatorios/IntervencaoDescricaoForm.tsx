import { AppInputField } from '@/components/app/app-input-field';
import { AppSelectField } from '@/components/app/app-input-select';
import { Button } from '@/components/ui/button';
import type { IntervencaoDescricaoOptions } from '@/types/internamento';
import type { IntervencaoDescricaoData } from '@/types/type';
import { router } from '@inertiajs/react';
import { useState } from 'react';

type Props = {
    pivotId: number;
    nome: string | null;
    existing: IntervencaoDescricaoData | null;
    options: IntervencaoDescricaoOptions;
    onSaved: () => void;
};

const SIM_NAO_NA = [
    { value: '1', label: 'Sim' },
    { value: '2', label: 'Não' },
    { value: '99', label: 'Não aplicável' },
];

const emptyForm = {
    anastemose_modo_id: '',
    anastemose_via_id: '',
    anastemose_sentido_id: '',
    tipo_de_reconstrucao_id: '',
    reforco_anastemose: '',
    localizacao_anastemose_id: '',
    confirmacao_anastemose_id: '',
    libertacao_angulo: '',
    numero_cargas: '',
    distancia_linha_pectinea_anastemose: '',
    distancia_linha_pectinea_tumor: '',
    qualidade_peca_operatoria_id: '',
    comentarios: '',
};

function dataToForm(data: IntervencaoDescricaoData | null): typeof emptyForm {
    if (!data) {
        return emptyForm;
    }

    const entries = Object.entries(emptyForm).map(([key]) => {
        const value = (data as unknown as Record<string, string | number | null>)[key];
        return [key, value == null ? '' : String(value)];
    });

    return Object.fromEntries(entries) as typeof emptyForm;
}

/** Descrição da anastomose de uma intervenção concreta (cirurgia de resseção de centro de referência). */
export default function IntervencaoDescricaoForm({ pivotId, nome, existing, options, onSaved }: Props) {
    const [form, setForm] = useState(dataToForm(existing));
    const [errors, setErrors] = useState<Record<string, string>>({});
    const [loading, setLoading] = useState(false);

    const updateField = (field: keyof typeof emptyForm, value: string) => {
        setForm((current) => ({ ...current, [field]: value }));
        setErrors((current) => ({ ...current, [field]: '' }));
    };

    const opt = (list: { id: number; nome: string }[]) => list.map((o) => ({ value: String(o.id), label: o.nome }));

    const handleSave = () => {
        setLoading(true);

        const requestOptions = {
            preserveScroll: true,
            onError: (formErrors: Record<string, string>) => setErrors(formErrors),
            onSuccess: () => onSaved(),
            onFinish: () => setLoading(false),
        };

        if (existing) {
            router.put(route('intervencao-descricaos.update', existing.id), form, requestOptions);
        } else {
            router.post(route('intervencao-descricaos.store'), { intervencao_id: pivotId, ...form }, requestOptions);
        }
    };

    return (
        <div className="rounded-xl border border-sky-300 bg-sky-50 p-4 dark:border-sky-900 dark:bg-sky-950/30">
            <h4 className="mb-3 text-sm font-semibold text-sky-800 dark:text-sky-300">Descrição da anastomose — {nome ?? 'intervenção'}</h4>

            <div className="grid grid-cols-1 gap-3 sm:grid-cols-3">
                <AppSelectField label="Modo da anastomose" value={form.anastemose_modo_id} onChange={(v) => updateField('anastemose_modo_id', v)} error={errors.anastemose_modo_id} options={opt(options.anastemoseModos)} />
                <AppSelectField label="Via da anastomose" value={form.anastemose_via_id} onChange={(v) => updateField('anastemose_via_id', v)} error={errors.anastemose_via_id} options={opt(options.anastemoseVias)} />
                <AppSelectField label="Sentido da anastomose" value={form.anastemose_sentido_id} onChange={(v) => updateField('anastemose_sentido_id', v)} error={errors.anastemose_sentido_id} options={opt(options.anastemoseSentidos)} />

                <AppSelectField label="Tipo de reconstrução" value={form.tipo_de_reconstrucao_id} onChange={(v) => updateField('tipo_de_reconstrucao_id', v)} error={errors.tipo_de_reconstrucao_id} options={opt(options.tiposDeReconstrucao)} />
                <AppSelectField label="Reforço da anastomose" value={form.reforco_anastemose} onChange={(v) => updateField('reforco_anastemose', v)} error={errors.reforco_anastemose} options={SIM_NAO_NA} />
                <AppSelectField label="Localização da anastomose" value={form.localizacao_anastemose_id} onChange={(v) => updateField('localizacao_anastemose_id', v)} error={errors.localizacao_anastemose_id} options={opt(options.localizacoesAnastemose)} />

                <AppSelectField label="Confirmação da anastomose" value={form.confirmacao_anastemose_id} onChange={(v) => updateField('confirmacao_anastemose_id', v)} error={errors.confirmacao_anastemose_id} options={opt(options.confirmacoesAnastemose)} />
                <AppSelectField label="Libertação do ângulo" value={form.libertacao_angulo} onChange={(v) => updateField('libertacao_angulo', v)} error={errors.libertacao_angulo} options={SIM_NAO_NA} />
                <AppInputField label="N.º de cargas" type="number" value={form.numero_cargas} onChange={(v) => updateField('numero_cargas', String(v))} error={errors.numero_cargas} />

                <AppInputField label="Distância à linha pectínea (anastomose, cm)" type="number" value={form.distancia_linha_pectinea_anastemose} onChange={(v) => updateField('distancia_linha_pectinea_anastemose', String(v))} error={errors.distancia_linha_pectinea_anastemose} />
                <AppInputField label="Distância à linha pectínea (tumor, cm)" type="number" value={form.distancia_linha_pectinea_tumor} onChange={(v) => updateField('distancia_linha_pectinea_tumor', String(v))} error={errors.distancia_linha_pectinea_tumor} />
                <AppSelectField label="Qualidade da peça operatória" value={form.qualidade_peca_operatoria_id} onChange={(v) => updateField('qualidade_peca_operatoria_id', v)} error={errors.qualidade_peca_operatoria_id} options={opt(options.qualidadesPecaOperatoria)} />

                <div className="sm:col-span-3">
                    <AppInputField label="Comentários" value={form.comentarios} onChange={(v) => updateField('comentarios', String(v))} error={errors.comentarios} />
                </div>
            </div>

            <div className="mt-3 flex justify-end">
                <Button type="button" size="sm" disabled={loading} onClick={handleSave}>
                    {loading ? 'A guardar...' : existing ? 'Guardar descrição' : 'Adicionar descrição'}
                </Button>
            </div>
        </div>
    );
}
