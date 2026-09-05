import { AppModal } from '@/components/app/app-modal';
import { Button } from '@/components/ui/button';
import { router } from '@inertiajs/react';
import { useState } from 'react';

type Complicacao = {
    id: number;
    nome: string;
    abrev?: string;
};

type Props = {
    internamento: {
        id: number;
        complicacaos: Complicacao[];
    };
    complicacoesAgrupadas?: Record<string, Complicacao[]> | null;
    onSave?: () => void;
    onClose?: () => void;
};

export default function AddComplicacoesToInternamento({ internamento, complicacoesAgrupadas, onSave, onClose }: Props) {
    const gruposAgrupados = complicacoesAgrupadas ?? {};
    const grupos = Object.keys(gruposAgrupados);

    const [grupoSelecionado, setGrupoSelecionado] = useState(grupos[0] ?? '');

    const [selectedComplicacaoId, setSelectedComplicacaoId] = useState<number | ''>('');

    const complicacoesDoGrupo = gruposAgrupados[grupoSelecionado] ?? [];

    const complicacoesAssociadasIds = new Set(internamento.complicacaos.map((c) => Number(c.id)));

    const complicacoesDisponiveis = complicacoesDoGrupo.filter((c) => !complicacoesAssociadasIds.has(c.id));

    const handleClose = () => {
        if (onClose) {
            onClose();
        } else {
            router.get(`/internamentos/${internamento.id}`);
        }
    };

    const handleAddComplicacao = async () => {
        if (!selectedComplicacaoId) return;

        await router.post(`/internamentos/${internamento.id}/complicacoes`, {
            complicacaoId: selectedComplicacaoId,
        });

        setSelectedComplicacaoId('');
        if (onSave) {
            onSave();
        } else {
            router.reload({ only: ['internamento'] });
        }
    };

    const handleRemoveComplicacao = async (complicacaoId: number) => {
        await router.delete(`/internamentos/${internamento.id}/complicacoes`, {
            data: {
                complicacaoId,
            },
        });
        if (onSave) {
            onSave();
        } else {
            router.reload({ only: ['internamento'] });
        }
    };

    return (
        <AppModal title="Complicações do Internamento" open={true} onClose={handleClose}>
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

                {/* Complicações disponíveis */}
                <div className="rounded-xl border bg-neutral-50 p-4 dark:bg-neutral-900">
                    <h2 className="mb-3 text-sm text-neutral-500">Complicações disponíveis ({grupoSelecionado})</h2>

                    <div className="flex items-end gap-4">
                        <select
                            className="w-full rounded-lg border bg-white p-2 dark:bg-neutral-800"
                            value={selectedComplicacaoId}
                            onChange={(e) => setSelectedComplicacaoId(e.target.value ? Number(e.target.value) : '')}
                        >
                            <option value="">Selecione uma complicação</option>

                            {complicacoesDisponiveis.map((c) => (
                                <option key={c.id} value={c.id}>
                                    {c.abrev ? `${c.abrev} — ` : ''}
                                    {c.nome}
                                </option>
                            ))}
                        </select>

                        <Button disabled={!selectedComplicacaoId} onClick={handleAddComplicacao}>
                            Adicionar
                        </Button>
                    </div>
                </div>

                {/* Complicações associadas */}
                <div className="rounded-xl border bg-neutral-50 p-4 dark:bg-neutral-900">
                    <h2 className="mb-3 text-sm text-neutral-500">Complicações associadas ao internamento</h2>

                    {internamento.complicacaos.length > 0 ? (
                        <ul className="space-y-2">
                            {internamento.complicacaos.map((c) => (
                                <li key={c.id} className="flex items-center justify-between rounded-lg border bg-white p-3 dark:bg-neutral-800">
                                    <div>
                                        <span className="font-medium">{c.nome}</span>
                                        {c.abrev && <span className="ml-2 text-neutral-500">({c.abrev})</span>}
                                    </div>

                                    <Button variant="destructive" onClick={() => handleRemoveComplicacao(c.id)}>
                                        Remover
                                    </Button>
                                </li>
                            ))}
                        </ul>
                    ) : (
                        <p className="text-neutral-500">Nenhuma complicação associada.</p>
                    )}
                </div>
            </div>
        </AppModal>
    );
}
