import { AppAvatar } from '@/components/app/app-avatar';
import { AppFilters } from '@/components/app/app-filters';
import { AppPageHeader } from '@/components/app/app-page-header';
import { AppPagination } from '@/components/app/app-pagination';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import type { BlocoOperatorioOptions, InternamentoOptions, LookupOption, ResolucaoComplicacaoOption } from '@/types/internamento';
import { InternamentoItem, Pagination } from '@/types/type';
import { Head, router } from '@inertiajs/react';
import { FileDown, Pencil, Plus, Scissors, Search, Stethoscope, Trash2 } from 'lucide-react';
import { useMemo, useState } from 'react';
import AddBlocoOperatorioToInternamento from '../BlocoOperatorios/AddBlocoOperatorioToInternamento';
import AddDiagnosticosToInternamento from '../Diagnosticos/AddDiagnosticosToInternamento';
import CreateOrUpdateInternamentoModal from './CreateOrUpdateInternamentoModal';

const breadcrumbs = [
    {
        title: 'Internamentos',
        href: route('internamentos.index'),
    },
];

type Props = {
    internamentos: Pagination<InternamentoItem>;
    filters: {
        search?: string;
        minhaEquipa?: boolean;
    };
    diagnosticosAgrupados: Record<string, { id: number; nome: string; abrev?: string }[]>;
    complicacoesOptions: LookupOption[];
    resolucoesComplicacaoOptions: ResolucaoComplicacaoOption[];
    blocoOperatorioOptions: BlocoOperatorioOptions;
    internamentoOptions: InternamentoOptions;
};

/** Formata "31-Aug (5 d)" a partir da data de entrada e dos dias decorridos. */
function formatEntrada(item: InternamentoItem) {
    if (!item.data_de_entrada) {
        return '-';
    }

    const formatted = new Intl.DateTimeFormat('pt-PT', {
        day: '2-digit',
        month: 'short',
    }).format(new Date(item.data_de_entrada));

    return item.dias_desde_entrada != null ? `${formatted} (${item.dias_desde_entrada} d)` : formatted;
}

export default function Index({
    internamentos,
    filters,
    diagnosticosAgrupados,
    complicacoesOptions,
    resolucoesComplicacaoOptions,
    blocoOperatorioOptions,
    internamentoOptions,
}: Props) {
    const [search, setSearch] = useState(filters.search ?? '');
    const [minhaEquipa, setMinhaEquipa] = useState(Boolean(filters.minhaEquipa));

    const [showInternamentoModal, setShowInternamentoModal] = useState(false);
    const [selectedInternamento, setSelectedInternamento] = useState<InternamentoItem | null>(null);

    const [diagnosticoTarget, setDiagnosticoTarget] = useState<InternamentoItem | null>(null);

    // guarda-se só o id: o objeto é sempre derivado dos dados mais recentes de `internamentos`,
    // para que o modal veja de imediato o bloco recém-criado (com as suas intervenções e ids de
    // pivot) sem ser preciso fechar e reabrir "Editar".
    const [blocoTargetId, setBlocoTargetId] = useState<number | null>(null);
    const blocoTarget = blocoTargetId != null ? (internamentos.data.find((item) => item.id === blocoTargetId) ?? null) : null;

    const grupos = useMemo(() => {
        const map = new Map<string, InternamentoItem[]>();

        internamentos.data.forEach((item) => {
            const chave = item.localizacao ?? 'Sem localização';
            const lista = map.get(chave) ?? [];
            lista.push(item);
            map.set(chave, lista);
        });

        return Array.from(map.entries());
    }, [internamentos.data]);

    const applyFilters = (overrides: Partial<{ search: string; minhaEquipa: boolean }> = {}) => {
        const nextSearch = overrides.search ?? search;
        const nextMinhaEquipa = overrides.minhaEquipa ?? minhaEquipa;

        router.get(
            route('internamentos.index'),
            {
                ...(nextSearch ? { search: nextSearch } : {}),
                ...(nextMinhaEquipa ? { minhaEquipa: '1' } : {}),
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            },
        );
    };

    const resetFilters = () => {
        setSearch('');
        setMinhaEquipa(false);

        router.get(route('internamentos.index'), {}, { preserveState: true, preserveScroll: true, replace: true });
    };

    const openInternamento = (internamento: InternamentoItem) => {
        setSelectedInternamento(internamento);
        setShowInternamentoModal(true);
    };

    const closeInternamento = () => {
        setShowInternamentoModal(false);
        setSelectedInternamento(null);
    };

    const handleDestroy = (internamento: InternamentoItem) => {
        if (!internamento.id) {
            return;
        }

        if (!window.confirm(`Remover o internamento de ${internamento.nome_curto ?? internamento.nome}?`)) {
            return;
        }

        router.delete(route('internamentos.destroy', internamento.id), { preserveScroll: true });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Internamentos" />

            <div className="p-6">
                <AppPageHeader
                    title="Lista de internamentos"
                    description="Gestão dos internamentos ativos dos pacientes."
                    action={
                        <div className="flex items-center gap-2">
                            <Button type="button" size="sm" onClick={() => router.get(route('internamentos.create'))}>
                                <Plus className="size-4" />
                                Adicionar
                            </Button>

                            <Button type="button" size="sm" variant="outline" disabled title="Brevemente disponível">
                                <FileDown className="size-4" />
                                Exportar para pdf
                            </Button>
                        </div>
                    }
                />

                <AppFilters
                    onSubmit={(event) => {
                        event.preventDefault();
                        applyFilters();
                    }}
                    onReset={resetFilters}
                >
                    <div className="relative w-full md:w-80">
                        <Search className="pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2 text-neutral-400" />
                        <input
                            type="text"
                            value={search}
                            onChange={(event) => setSearch(event.target.value)}
                            placeholder="Procurar por nome, processo, cama ou motivo"
                            className="border-input bg-background ring-offset-background placeholder:text-muted-foreground focus-visible:ring-ring w-full rounded-full border py-2 pr-3 pl-9 text-sm focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:outline-none"
                        />
                    </div>

                    <label className="flex items-center gap-2 text-sm text-neutral-600 select-none dark:text-neutral-300">
                        <input
                            type="checkbox"
                            checked={minhaEquipa}
                            onChange={(event) => {
                                setMinhaEquipa(event.target.checked);
                                applyFilters({ minhaEquipa: event.target.checked });
                            }}
                            className="accent-primary size-4 rounded"
                        />
                        A minha equipa
                    </label>
                </AppFilters>

                {internamentos.data.length === 0 ? (
                    <p className="text-muted-foreground">Nenhum internamento encontrado.</p>
                ) : (
                    <div className="overflow-hidden rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                        <div className="overflow-x-auto">
                            <table className="w-full text-sm">
                                <thead className="border-b border-neutral-200 bg-neutral-50 dark:border-neutral-800 dark:bg-neutral-950">
                                    <tr>
                                        {['Cama', 'Processo', 'Nome', 'Entrada', 'Responsável', 'Motivo de Internamento', 'Diagnóstico(s)', 'BlocoOperatório(s)', 'Opções'].map(
                                            (label) => (
                                                <th
                                                    key={label}
                                                    scope="col"
                                                    className="px-4 py-3 text-left text-xs font-semibold tracking-wide text-neutral-500 uppercase dark:text-neutral-400"
                                                >
                                                    {label}
                                                </th>
                                            ),
                                        )}
                                    </tr>
                                </thead>

                                {grupos.map(([localizacao, items]) => (
                                    <tbody key={localizacao} className="divide-y divide-neutral-200 dark:divide-neutral-800">
                                        <tr className="bg-neutral-100 dark:bg-neutral-800/60">
                                            <td colSpan={9} className="px-4 py-2 text-xs font-bold tracking-wide text-neutral-600 uppercase dark:text-neutral-300">
                                                {localizacao}
                                            </td>
                                        </tr>

                                        {items.map((item) => (
                                            <tr key={item.id} className="group transition-colors hover:bg-neutral-50 dark:hover:bg-neutral-800/40">
                                                <td className="px-4 py-3.5 align-top font-medium text-neutral-700 dark:text-neutral-300">{item.cama}</td>
                                                <td className="px-4 py-3.5 align-top text-neutral-700 dark:text-neutral-300">{item.numero_processo}</td>
                                                <td className="px-4 py-3.5 align-top">
                                                    <div className="flex items-center gap-3">
                                                        <AppAvatar name={item.nome_curto ?? '?'} />
                                                        <span className="font-medium text-neutral-900 dark:text-white">{item.nome_curto}</span>
                                                    </div>
                                                </td>
                                                <td className="px-4 py-3.5 align-top whitespace-nowrap text-neutral-700 dark:text-neutral-300">
                                                    {formatEntrada(item)}
                                                </td>
                                                <td className="px-4 py-3.5 align-top text-neutral-700 dark:text-neutral-300">{item.responsavel}</td>
                                                <td className="px-4 py-3.5 align-top text-neutral-700 dark:text-neutral-300">{item.motivo_internamento}</td>

                                                {/* Diagnóstico(s) */}
                                                <td className="px-4 py-3.5 align-top">
                                                    <div className="space-y-1">
                                                        {item.diagnosticos?.map((diagnostico) => (
                                                            <div key={diagnostico.id} className="text-xs text-neutral-600 dark:text-neutral-400">
                                                                {diagnostico.nome}
                                                            </div>
                                                        ))}
                                                        <button
                                                            type="button"
                                                            onClick={() => setDiagnosticoTarget(item)}
                                                            className="inline-flex items-center gap-1 text-xs font-medium text-green-600 hover:underline dark:text-green-400"
                                                        >
                                                            <Stethoscope className="size-3.5" />
                                                            Adicionar
                                                        </button>
                                                    </div>
                                                </td>

                                                {/* Bloco Operatório(s) */}
                                                <td className="px-4 py-3.5 align-top">
                                                    <div className="space-y-1">
                                                        {item.bloco_operatorios?.map((bloco) => (
                                                            <div key={bloco.id} className="text-xs text-neutral-600 dark:text-neutral-400">
                                                                {bloco.data_de_inicio} {bloco.tipo_de_cirurgia ? `(${bloco.tipo_de_cirurgia})` : ''}
                                                            </div>
                                                        ))}
                                                        <button
                                                            type="button"
                                                            onClick={() => item.id && setBlocoTargetId(item.id)}
                                                            className="inline-flex items-center gap-1 text-xs font-medium text-blue-600 hover:underline dark:text-blue-400"
                                                        >
                                                            <Scissors className="size-3.5" />
                                                            Adicionar
                                                        </button>
                                                    </div>
                                                </td>

                                                {/* Opções */}
                                                <td className="px-4 py-3.5 align-top">
                                                    <div className="flex items-center gap-3">
                                                        <button
                                                            type="button"
                                                            onClick={() => openInternamento(item)}
                                                            className="inline-flex items-center gap-1 text-xs font-medium text-neutral-600 hover:underline dark:text-neutral-300"
                                                        >
                                                            <Pencil className="size-3.5" />
                                                            Editar
                                                        </button>
                                                        <button
                                                            type="button"
                                                            onClick={() => handleDestroy(item)}
                                                            className="inline-flex items-center gap-1 text-xs font-medium text-red-600 hover:underline dark:text-red-400"
                                                        >
                                                            <Trash2 className="size-3.5" />
                                                            Apagar
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                ))}
                            </table>
                        </div>
                    </div>
                )}

                <AppPagination
                    links={internamentos.links}
                    from={internamentos.from ?? undefined}
                    to={internamentos.to ?? undefined}
                    total={internamentos.total ?? undefined}
                />

                {selectedInternamento && (
                    <CreateOrUpdateInternamentoModal
                        open={showInternamentoModal}
                        onClose={closeInternamento}
                        internamento={selectedInternamento}
                        utenteId={selectedInternamento.utente_id}
                        internamentoOptions={internamentoOptions}
                        complicacoesOptions={complicacoesOptions}
                        resolucoesComplicacaoOptions={resolucoesComplicacaoOptions}
                    />
                )}

                {diagnosticoTarget && diagnosticoTarget.id && (
                    <AddDiagnosticosToInternamento
                        internamento={{ id: diagnosticoTarget.id, diagnosticos: diagnosticoTarget.diagnosticos ?? [] }}
                        diagnosticosAgrupados={diagnosticosAgrupados}
                        onClose={() => setDiagnosticoTarget(null)}
                        onSave={() => {
                            setDiagnosticoTarget(null);
                            router.reload({ only: ['internamentos'] });
                        }}
                    />
                )}

                {blocoTarget && blocoTarget.id && (
                    <AddBlocoOperatorioToInternamento
                        internamento={{ id: blocoTarget.id, bloco_operatorios: blocoTarget.bloco_operatorios ?? [] }}
                        options={blocoOperatorioOptions}
                        onClose={() => setBlocoTargetId(null)}
                        onSave={() => router.reload({ only: ['internamentos'] })}
                    />
                )}
            </div>
        </AppLayout>
    );
}
