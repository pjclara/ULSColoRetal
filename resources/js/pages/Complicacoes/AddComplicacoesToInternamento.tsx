import AppMultiSelect from '@/components/app/app-multi-select';
import { AppModal } from '@/components/app/app-modal';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { router } from '@inertiajs/react';
import { Pencil, Plus, Search } from 'lucide-react';
import { useMemo, useState } from 'react';

type Complicacao = {
    id: number;
    nome: string;
    abrev?: string;
};

type ComplicacaoAssociada = Complicacao & {
    resolucao_ids?: (number | string)[];
};

type ResolucaoOption = {
    id: number;
    nome: string;
};

type Props = {
    internamento: {
        id: number;
        complicacaos: ComplicacaoAssociada[];
    };
    complicacoesAgrupadas?: Record<string, Complicacao[]> | null;
    resolucoesComplicacaoOptions?: ResolucaoOption[];
    onSave?: () => void;
    onClose?: () => void;
};

export default function AddComplicacoesToInternamento({
    internamento,
    complicacoesAgrupadas,
    resolucoesComplicacaoOptions,
    onSave,
    onClose,
}: Props) {
    const todasComplicacoes = useMemo(() => Object.values(complicacoesAgrupadas ?? {}).flat(), [complicacoesAgrupadas]);
    const resolucaoOptions = resolucoesComplicacaoOptions ?? [];

    const [filtro, setFiltro] = useState('');
    const [editandoId, setEditandoId] = useState<number | null>(null);
    const [resolucaoSelecionada, setResolucaoSelecionada] = useState<string[]>([]);
    const [guardandoId, setGuardandoId] = useState<number | null>(null);
    const [tentouGuardar, setTentouGuardar] = useState(false);

    const complicacoesAssociadasIds = new Set(internamento.complicacaos.map((c) => Number(c.id)));

    const complicacoesDisponiveis = todasComplicacoes
        .filter((c) => !complicacoesAssociadasIds.has(c.id))
        .filter((c) => {
            const termo = filtro.trim().toLowerCase();
            if (!termo) return true;
            return c.nome.toLowerCase().includes(termo) || (c.abrev ?? '').toLowerCase().includes(termo);
        });

    const handleClose = () => {
        if (onClose) {
            onClose();
        } else {
            router.get(`/internamentos/${internamento.id}`);
        }
    };

    const iniciarEdicao = (complicacaoId: number, resolucaoAtual: (number | string)[] = []) => {
        setEditandoId(complicacaoId);
        setResolucaoSelecionada(resolucaoAtual.map(String));
        setTentouGuardar(false);
    };

    const cancelarEdicao = () => {
        setEditandoId(null);
        setResolucaoSelecionada([]);
        setTentouGuardar(false);
    };

    const handleConfirmarComplicacao = async (complicacaoId: number) => {
        setTentouGuardar(true);

        if (resolucaoSelecionada.length === 0) {
            return;
        }

        setGuardandoId(complicacaoId);

        await router.post(
            `/internamentos/${internamento.id}/complicacoes`,
            { complicacaoId, resolucao: resolucaoSelecionada.map(Number) },
            {
                preserveState: true,
                preserveScroll: true,
                onFinish: () => setGuardandoId(null),
            },
        );

        cancelarEdicao();

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
            preserveState: true,
            preserveScroll: true,
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
                {/* Complicações disponíveis */}
                <div className="rounded-xl border bg-neutral-50 p-4 dark:bg-neutral-900">
                    <h2 className="mb-3 text-sm text-neutral-500">Complicações disponíveis</h2>

                    <div className="relative mb-3">
                        <Search className="absolute top-1/2 left-3 size-4 -translate-y-1/2 text-neutral-400" />
                        <Input
                            value={filtro}
                            onChange={(e) => setFiltro(e.target.value)}
                            placeholder="Filtrar complicações..."
                            className="pl-9"
                        />
                    </div>

                    <div className="max-h-64 space-y-1 overflow-y-auto">
                        {complicacoesDisponiveis.length > 0 ? (
                            complicacoesDisponiveis.map((c) => (
                                <div key={c.id} className="rounded-lg border bg-white px-3 py-2 dark:bg-neutral-800">
                                    <div className="flex items-center justify-between">
                                        <span>
                                            {c.nome}
                                            {c.abrev && <span className="ml-2 text-neutral-500">({c.abrev})</span>}
                                        </span>

                                        {editandoId !== c.id && (
                                            <Button size="sm" variant="outline" onClick={() => iniciarEdicao(c.id)}>
                                                <Plus className="size-3.5" />
                                                Adicionar
                                            </Button>
                                        )}
                                    </div>

                                    {editandoId === c.id && (
                                        <div className="mt-2 space-y-2">
                                            <AppMultiSelect
                                                label="Resolução"
                                                value={resolucaoSelecionada}
                                                onChange={setResolucaoSelecionada}
                                                options={resolucaoOptions.map((option) => ({
                                                    value: String(option.id),
                                                    label: option.nome,
                                                }))}
                                                placeholder="Selecionar resolução..."
                                                searchPlaceholder="Pesquisar resolução..."
                                                emptyMessage="Nenhuma resolução encontrada."
                                                error={
                                                    tentouGuardar && resolucaoSelecionada.length === 0
                                                        ? 'Escolha pelo menos uma resolução para esta complicação.'
                                                        : undefined
                                                }
                                            />

                                            <div className="flex justify-end gap-2">
                                                <Button size="sm" variant="ghost" onClick={cancelarEdicao}>
                                                    Cancelar
                                                </Button>
                                                <Button
                                                    size="sm"
                                                    disabled={guardandoId === c.id}
                                                    onClick={() => handleConfirmarComplicacao(c.id)}
                                                >
                                                    Confirmar
                                                </Button>
                                            </div>
                                        </div>
                                    )}
                                </div>
                            ))
                        ) : (
                            <p className="p-2 text-sm text-neutral-500">Nenhuma complicação encontrada.</p>
                        )}
                    </div>
                </div>

                {/* Complicações associadas */}
                <div className="rounded-xl border bg-neutral-50 p-4 dark:bg-neutral-900">
                    <h2 className="mb-3 text-sm text-neutral-500">Complicações associadas ao internamento</h2>

                    {internamento.complicacaos.length > 0 ? (
                        <ul className="space-y-2">
                            {internamento.complicacaos.map((c) => {
                                const resolucaoNomes = (c.resolucao_ids ?? []).map(
                                    (id) => resolucaoOptions.find((option) => String(option.id) === String(id))?.nome ?? String(id),
                                );

                                return (
                                    <li key={c.id} className="rounded-lg border bg-white p-3 dark:bg-neutral-800">
                                        <div className="flex items-center justify-between">
                                            <span className="font-medium">
                                                {c.nome}
                                                {c.abrev && <span className="ml-2 text-neutral-500">({c.abrev})</span>}
                                            </span>

                                            <div className="flex items-center gap-2">
                                                <Button
                                                    size="sm"
                                                    variant="outline"
                                                    onClick={() => iniciarEdicao(c.id, c.resolucao_ids ?? [])}
                                                >
                                                    <Pencil className="size-3.5" />
                                                    Resolução
                                                </Button>
                                                <Button variant="destructive" size="sm" onClick={() => handleRemoveComplicacao(c.id)}>
                                                    Remover
                                                </Button>
                                            </div>
                                        </div>

                                        {editandoId === c.id ? (
                                            <div className="mt-2 space-y-2">
                                                <AppMultiSelect
                                                    label="Resolução"
                                                    value={resolucaoSelecionada}
                                                    onChange={setResolucaoSelecionada}
                                                    options={resolucaoOptions.map((option) => ({
                                                        value: String(option.id),
                                                        label: option.nome,
                                                    }))}
                                                    placeholder="Selecionar resolução..."
                                                    searchPlaceholder="Pesquisar resolução..."
                                                    emptyMessage="Nenhuma resolução encontrada."
                                                    error={
                                                        tentouGuardar && resolucaoSelecionada.length === 0
                                                            ? 'Escolha pelo menos uma resolução para esta complicação.'
                                                            : undefined
                                                    }
                                                />

                                                <div className="flex justify-end gap-2">
                                                    <Button size="sm" variant="ghost" onClick={cancelarEdicao}>
                                                        Cancelar
                                                    </Button>
                                                    <Button
                                                        size="sm"
                                                        disabled={guardandoId === c.id}
                                                        onClick={() => handleConfirmarComplicacao(c.id)}
                                                    >
                                                        Confirmar
                                                    </Button>
                                                </div>
                                            </div>
                                        ) : (
                                            <p className="mt-1 text-sm text-neutral-500">
                                                {resolucaoNomes.length > 0 ? resolucaoNomes.join(', ') : 'Sem resolução escolhida.'}
                                            </p>
                                        )}
                                    </li>
                                );
                            })}
                        </ul>
                    ) : (
                        <p className="text-neutral-500">Nenhuma complicação associada.</p>
                    )}
                </div>
            </div>
        </AppModal>
    );
}
