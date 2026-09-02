import { AppModal } from '@/components/app/app-modal';
import { Button } from '@/components/ui/button';
import { router } from '@inertiajs/react';
import { useState } from 'react';

type Diagnostico = {
    id: number;
    nome: string;
    abrev?: string;
    centro_de_referencia?: number;
};

type Props = {
    internamento: {
        id: number;
        diagnosticos: Diagnostico[];
    };
    diagnosticosAgrupados?: Record<string, Diagnostico[]> | null;
    onSave?: () => void;
};

export default function ShowDiagnosticos({ internamento, diagnosticosAgrupados, onSave }: Props) {
    const gruposAgrupados = diagnosticosAgrupados ?? {};
    const grupos = Object.keys(gruposAgrupados);

    const [grupoSelecionado, setGrupoSelecionado] = useState(grupos[0] ?? '');

    const [selectedDiagnosticoId, setSelectedDiagnosticoId] = useState<number | ''>('');

    const diagnosticosDoGrupo = gruposAgrupados[grupoSelecionado] ?? [];

    const diagnosticosAssociadosIds = new Set(internamento.diagnosticos.map((d) => Number(d.id)));

    const diagnosticosDisponiveis = diagnosticosDoGrupo.filter((d) => !diagnosticosAssociadosIds.has(d.id));

    const handleAddDiagnostico = async () => {
        if (!selectedDiagnosticoId) return;

        await router.post(`/internamentos/${internamento.id}/diagnosticos`, {
            diagnosticoId: selectedDiagnosticoId,
        });

        setSelectedDiagnosticoId('');
        if (onSave) {
            onSave();
        } else {
            router.reload({ only: ['internamento'] });
        }
    };

    const handleRemoveDiagnostico = async (diagnosticoId: number) => {
        await router.delete(`/internamentos/${internamento.id}/diagnosticos`, {
            data: {
                diagnosticoId,
            },
        });
        if (onSave) {
            onSave();
        } else {
            router.reload({ only: ['internamento'] });
        }
    };

    return (
        <AppModal title="Diagnósticos do Internamento" open={true} onClose={() => router.get(`/internamentos/${internamento.id}`)}>
            <div className="space-y-6 p-2">
                {/* Filtros */}
                <div className="rounded-xl border bg-neutral-50 p-4 dark:bg-neutral-900">
                    <h2 className="mb-3 text-sm text-neutral-500">Filtrar por grupo</h2>

                    <div className="flex flex-wrap gap-2">
                        {grupos.map((grupo) => (
                            <Button
                                key={grupo}
                                variant={grupoSelecionado === grupo ? 'default' : 'outline'}
                                onClick={() => setGrupoSelecionado(grupo)}
                            >
                                {grupo}
                            </Button>
                        ))}
                    </div>
                </div>

                {/* Diagnósticos disponíveis */}
                <div className="rounded-xl border bg-neutral-50 p-4 dark:bg-neutral-900">
                    <h2 className="mb-3 text-sm text-neutral-500">Diagnósticos disponíveis ({grupoSelecionado})</h2>

                    <div className="flex items-end gap-4">
                        <select
                            className="w-full rounded-lg border bg-white p-2 dark:bg-neutral-800"
                            value={selectedDiagnosticoId}
                            onChange={(e) => setSelectedDiagnosticoId(e.target.value ? Number(e.target.value) : '')}
                        >
                            <option value="">Selecione um diagnóstico</option>

                            {diagnosticosDisponiveis.map((d) => (
                                <option key={d.id} value={d.id}>
                                    {d.abrev ? `${d.abrev} — ` : ''}
                                    {d.nome}
                                </option>
                            ))}
                        </select>

                        <Button disabled={!selectedDiagnosticoId} onClick={handleAddDiagnostico}>
                            Adicionar
                        </Button>
                    </div>
                </div>

                {/* Diagnósticos associados */}
                <div className="rounded-xl border bg-neutral-50 p-4 dark:bg-neutral-900">
                    <h2 className="mb-3 text-sm text-neutral-500">Diagnósticos associados ao internamento</h2>

                    {internamento.diagnosticos.length > 0 ? (
                        <ul className="space-y-2">
                            {internamento.diagnosticos.map((d) => (
                                <li key={d.id} className="flex items-center justify-between rounded-lg border bg-white p-3 dark:bg-neutral-800">
                                    <div>
                                        <span className="font-medium">{d.nome}</span>
                                        {d.abrev && <span className="ml-2 text-neutral-500">({d.abrev})</span>}
                                    </div>

                                    <Button variant="destructive" onClick={() => handleRemoveDiagnostico(d.id)}>
                                        Remover
                                    </Button>
                                </li>
                            ))}
                        </ul>
                    ) : (
                        <p className="text-neutral-500">Nenhum diagnóstico associado.</p>
                    )}
                </div>
            </div>
        </AppModal>
    );
}
