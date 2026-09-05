import { AppPageHeader } from '@/components/app/app-page-header';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { Head, router } from '@inertiajs/react';
import { Info } from 'lucide-react';
import type { ReactNode } from 'react';

type LinhaAuditoria = {
    periodo: string;
    internamentos: number;
    mediana_ti: number | null;
    obitos: number;
    obitos_pct: number | null;
    doentes_operados: number;
    mediana_ti_operados: number | null;
    programada: number;
    mediana_programada: number | null;
    urgencia: number;
    mediana_urgencia: number | null;
    outras: number;
    total_cirurgias: number;
    complicacoes_ligeiras: number;
    complicacoes_ligeiras_pct: number | null;
    complicacoes_graves: number;
    complicacoes_graves_pct: number | null;
    deiscencias: number;
    deiscencias_pct: number | null;
    obitos_entre_operados: number;
    obitos_entre_operados_pct: number | null;
};

type Props = {
    linhas: LinhaAuditoria[];
    periodo: 'ano' | 'semestre' | 'trimestre';
};

const breadcrumbs = [
    {
        title: 'Auditoria',
        href: route('auditoria.index'),
    },
];

const PERIODOS: { value: Props['periodo']; label: string }[] = [
    { value: 'ano', label: 'Por ano' },
    { value: 'semestre', label: 'Por semestre' },
    { value: 'trimestre', label: 'Por trimestre' },
];

function fmtDias(value: number | null) {
    return value == null ? '—' : `Mediana ${value} d`;
}

function fmtPct(count: number, pct: number | null) {
    return pct == null ? `${count}` : `${count} (${pct.toFixed(2)}%)`;
}

/** Célula com um valor principal e, por baixo, um texto secundário mais pequeno (ex: a mediana). */
function StatCell({ value, sub }: { value: ReactNode; sub?: ReactNode }) {
    return (
        <div className="leading-tight">
            <div>{value}</div>
            {sub && <div className="text-[11px] text-neutral-400 dark:text-neutral-500">{sub}</div>}
        </div>
    );
}

export default function Index({ linhas, periodo }: Props) {
    const trocarPeriodo = (novoPeriodo: Props['periodo']) => {
        router.get(route('auditoria.index'), { periodo: novoPeriodo }, { preserveState: true, preserveScroll: true, replace: true });
    };

    const colunas: { key: string; label: string; render: (linha: LinhaAuditoria) => ReactNode }[] = [
        { key: 'periodo', label: 'Período', render: (l) => l.periodo },
        {
            key: 'internamentos',
            label: 'Internamentos',
            render: (l) => <StatCell value={l.internamentos} sub={fmtDias(l.mediana_ti)} />,
        },
        { key: 'obitos', label: 'Óbitos', render: (l) => fmtPct(l.obitos, l.obitos_pct) },
        {
            key: 'doentes_operados',
            label: 'Operados',
            render: (l) => <StatCell value={l.doentes_operados} sub={fmtDias(l.mediana_ti_operados)} />,
        },
        {
            key: 'programada',
            label: 'Programada',
            render: (l) => <StatCell value={l.programada} sub={fmtDias(l.mediana_programada)} />,
        },
        {
            key: 'urgencia',
            label: 'Urgência',
            render: (l) => <StatCell value={l.urgencia} sub={fmtDias(l.mediana_urgencia)} />,
        },
        { key: 'outras', label: 'Outras', render: (l) => l.outras },
        {
            key: 'complicacoes_ligeiras',
            label: 'Compl. ligeiras',
            render: (l) => fmtPct(l.complicacoes_ligeiras, l.complicacoes_ligeiras_pct),
        },
        {
            key: 'complicacoes_graves',
            label: 'Compl. graves',
            render: (l) => fmtPct(l.complicacoes_graves, l.complicacoes_graves_pct),
        },
        { key: 'deiscencias', label: 'Deiscências', render: (l) => fmtPct(l.deiscencias, l.deiscencias_pct) },
        {
            key: 'obitos_entre_operados',
            label: 'Óbitos operados',
            render: (l) => fmtPct(l.obitos_entre_operados, l.obitos_entre_operados_pct),
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Auditoria" />

            <div className="p-6">
                <AppPageHeader
                    title="Auditoria"
                    description="Estatísticas de internamentos e cirurgias, agregadas por período."
                    action={
                        <div className="flex items-center gap-1 rounded-full border border-neutral-200 bg-white p-1 dark:border-neutral-800 dark:bg-neutral-900">
                            {PERIODOS.map((option) => (
                                <Button
                                    key={option.value}
                                    type="button"
                                    size="sm"
                                    variant={periodo === option.value ? 'default' : 'ghost'}
                                    className="rounded-full"
                                    onClick={() => trocarPeriodo(option.value)}
                                >
                                    {option.label}
                                </Button>
                            ))}
                        </div>
                    }
                />

                <div className="mb-4 flex items-start gap-2 rounded-xl border border-blue-200 bg-blue-50 p-3 text-xs text-blue-800 dark:border-blue-900 dark:bg-blue-950/30 dark:text-blue-300">
                    <Info className="mt-0.5 size-4 shrink-0" />
                    <p>
                        O período é definido pela data de entrada do internamento. Urgência/Programada/Outras contam <strong>cirurgias</strong> (um doente
                        pode ter mais do que uma no mesmo período) — Programada inclui as cirurgias adicionais. Complicações ligeiras/graves seguem a
                        classificação Clavien-Dindo do internamento (ligeiras = Grau 1-2, graves = Grau 3A-5). Complicações, Deiscências e Óbitos entre
                        operados são percentagens sobre o total de cirurgias do período.
                    </p>
                </div>

                {linhas.length === 0 ? (
                    <p className="text-muted-foreground">Sem dados para apresentar.</p>
                ) : (
                    <div className="overflow-hidden rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                        <div className="overflow-x-auto">
                            <table className="w-full text-sm">
                                <thead className="border-b border-neutral-200 bg-neutral-50 dark:border-neutral-800 dark:bg-neutral-950">
                                    <tr>
                                        {colunas.map((coluna) => (
                                            <th
                                                key={coluna.key}
                                                scope="col"
                                                className="px-2.5 py-2.5 text-left text-xs font-semibold tracking-wide whitespace-nowrap text-neutral-500 uppercase dark:text-neutral-400"
                                            >
                                                {coluna.label}
                                            </th>
                                        ))}
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-neutral-200 dark:divide-neutral-800">
                                    {linhas.map((linha) => (
                                        <tr key={linha.periodo} className="hover:bg-neutral-50 dark:hover:bg-neutral-800/40">
                                            {colunas.map((coluna) => (
                                                <td
                                                    key={coluna.key}
                                                    className={`px-2.5 py-2 whitespace-nowrap text-neutral-700 dark:text-neutral-300 ${
                                                        coluna.key === 'periodo' ? 'font-semibold text-neutral-900 dark:text-white' : ''
                                                    }`}
                                                >
                                                    {coluna.render(linha)}
                                                </td>
                                            ))}
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
