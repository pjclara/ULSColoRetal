import { AppInputField } from '@/components/app/app-input-field';
import { AppSelectField } from '@/components/app/app-input-select';
import { Button } from '@/components/ui/button';
import type { BlocoOperatorioCRSOptions } from '@/types/internamento';
import type { BlocoOperatorioCRSData } from '@/types/type';
import { router } from '@inertiajs/react';
import { useState } from 'react';

type Props = {
    blocoOperatorioId: number;
    existing: BlocoOperatorioCRSData | null;
    options: BlocoOperatorioCRSOptions;
    onSaved: () => void;
};

const SIM_NAO_NA = [
    { value: '1', label: 'Sim' },
    { value: '2', label: 'Não' },
    { value: '99', label: 'Não aplicável' },
];

const emptyForm = {
    experiencia_cirurgiao_id: '',
    asa: '',
    mortalidade: '',
    score_fisiologico: '',
    score_gravidade_cirurgico: '',
    preparacao_intestinal_id: '',
    intencao_id: '',
    paleativa_causa: '',
    duracao: '',
    estoma_de_protecao_id: '',
    local_extracao_peca_id: '',
    tipo_de_dreno_id: '',
    resseccao_multi_orgao: '',
    resseccao_multi_orgao_quais: '',
    aderencia_id: '',
    tipo_de_resseccao_id: '',
    neoplasia_residual_id: '',
    perdas_hematica_id: '',
    transfusao_intra_operatoria: '',
    unidades_globulos: '',
    protector_de_parede: '',
    complicacoes: '',
    complicacoes_quais: '',
};

function dataToForm(data: BlocoOperatorioCRSData | null): typeof emptyForm {
    if (!data) {
        return emptyForm;
    }

    const entries = Object.entries(emptyForm).map(([key]) => {
        const value = (data as unknown as Record<string, string | number | null>)[key];
        return [key, value == null ? '' : String(value)];
    });

    return Object.fromEntries(entries) as typeof emptyForm;
}

/** Dados clínicos adicionais (centro de referência) de um bloco operatório com cirurgia de resseção. */
export default function BlocoOperatorioCRSForm({ blocoOperatorioId, existing, options, onSaved }: Props) {
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
            router.put(route('bloco-operatorio-c-rs.update', existing.id), form, requestOptions);
        } else {
            router.post(route('bloco-operatorio-c-rs.store'), { bloco_operatorio_id: blocoOperatorioId, ...form }, requestOptions);
        }
    };

    return (
        <div className="rounded-xl border border-amber-300 bg-amber-50 p-4 dark:border-amber-900 dark:bg-amber-950/30">
            <h3 className="mb-1 text-sm font-semibold text-amber-800 dark:text-amber-300">Dados de Centro de Referência</h3>
            <p className="mb-3 text-xs text-amber-700 dark:text-amber-400">
                Obrigatório porque uma das intervenções selecionadas é uma cirurgia de resseção de centro de referência.
            </p>

            <div className="grid grid-cols-1 gap-3 sm:grid-cols-3">
                <AppInputField label="Duração (min)" type="number" value={form.duracao} onChange={(v) => updateField('duracao', String(v))} error={errors.duracao} />
                <AppSelectField label="Intenção" value={form.intencao_id} onChange={(v) => updateField('intencao_id', v)} error={errors.intencao_id} options={opt(options.intencoes)} />
                <AppInputField label="Causa paliativa" value={form.paleativa_causa} onChange={(v) => updateField('paleativa_causa', String(v))} error={errors.paleativa_causa} />

                <AppSelectField label="Estoma de proteção" value={form.estoma_de_protecao_id} onChange={(v) => updateField('estoma_de_protecao_id', v)} error={errors.estoma_de_protecao_id} options={opt(options.estomasDeProtecao)} />
                <AppSelectField label="Local de extração da peça" value={form.local_extracao_peca_id} onChange={(v) => updateField('local_extracao_peca_id', v)} error={errors.local_extracao_peca_id} options={opt(options.locaisExtracaoPeca)} />
                <AppSelectField label="Tipo de dreno" value={form.tipo_de_dreno_id} onChange={(v) => updateField('tipo_de_dreno_id', v)} error={errors.tipo_de_dreno_id} options={opt(options.tiposDeDreno)} />

                <AppSelectField label="Resseção multiorgão" value={form.resseccao_multi_orgao} onChange={(v) => updateField('resseccao_multi_orgao', v)} error={errors.resseccao_multi_orgao} options={SIM_NAO_NA} />
                <div className="sm:col-span-2">
                    <AppInputField label="Resseção multiorgão — quais" value={form.resseccao_multi_orgao_quais} onChange={(v) => updateField('resseccao_multi_orgao_quais', String(v))} error={errors.resseccao_multi_orgao_quais} />
                </div>

                <AppSelectField label="Aderências" value={form.aderencia_id} onChange={(v) => updateField('aderencia_id', v)} error={errors.aderencia_id} options={opt(options.aderencias)} />
                <AppSelectField label="Tipo de resseção" value={form.tipo_de_resseccao_id} onChange={(v) => updateField('tipo_de_resseccao_id', v)} error={errors.tipo_de_resseccao_id} options={opt(options.tiposDeResseccao)} />
                <AppSelectField label="Neoplasia residual" value={form.neoplasia_residual_id} onChange={(v) => updateField('neoplasia_residual_id', v)} error={errors.neoplasia_residual_id} options={opt(options.neoplasiasResiduais)} />

                <AppSelectField label="Perdas hemáticas" value={form.perdas_hematica_id} onChange={(v) => updateField('perdas_hematica_id', v)} error={errors.perdas_hematica_id} options={opt(options.perdasHematicas)} />
                <AppSelectField label="Transfusão intra-operatória" value={form.transfusao_intra_operatoria} onChange={(v) => updateField('transfusao_intra_operatoria', v)} error={errors.transfusao_intra_operatoria} options={SIM_NAO_NA} />
                <AppInputField label="Unidades de glóbulos" type="number" value={form.unidades_globulos} onChange={(v) => updateField('unidades_globulos', String(v))} error={errors.unidades_globulos} />

                <AppSelectField label="Protetor de parede" value={form.protector_de_parede} onChange={(v) => updateField('protector_de_parede', v)} error={errors.protector_de_parede} options={SIM_NAO_NA} />
                <AppSelectField label="Complicações" value={form.complicacoes} onChange={(v) => updateField('complicacoes', v)} error={errors.complicacoes} options={SIM_NAO_NA} />
                <AppInputField label="Complicações — quais" value={form.complicacoes_quais} onChange={(v) => updateField('complicacoes_quais', String(v))} error={errors.complicacoes_quais} />

                <AppInputField label="ASA" type="number" value={form.asa} onChange={(v) => updateField('asa', String(v))} error={errors.asa} />
                <AppInputField label="Mortalidade prevista (%)" type="number" value={form.mortalidade} onChange={(v) => updateField('mortalidade', String(v))} error={errors.mortalidade} />
                <AppInputField label="Score fisiológico" type="number" value={form.score_fisiologico} onChange={(v) => updateField('score_fisiologico', String(v))} error={errors.score_fisiologico} />

                <AppInputField label="Score gravidade cirúrgico" type="number" value={form.score_gravidade_cirurgico} onChange={(v) => updateField('score_gravidade_cirurgico', String(v))} error={errors.score_gravidade_cirurgico} />
                <AppInputField label="Experiência do cirurgião" type="number" value={form.experiencia_cirurgiao_id} onChange={(v) => updateField('experiencia_cirurgiao_id', String(v))} error={errors.experiencia_cirurgiao_id} />
                <AppInputField label="Preparação intestinal" type="number" value={form.preparacao_intestinal_id} onChange={(v) => updateField('preparacao_intestinal_id', String(v))} error={errors.preparacao_intestinal_id} />
            </div>

            <div className="mt-3 flex justify-end">
                <Button type="button" size="sm" disabled={loading} onClick={handleSave}>
                    {loading ? 'A guardar...' : existing ? 'Guardar dados de centro de referência' : 'Adicionar dados de centro de referência'}
                </Button>
            </div>
        </div>
    );
}
