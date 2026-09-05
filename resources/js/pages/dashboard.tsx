import { AppAvatar } from '@/components/app/app-avatar';
import { AppPageHeader } from '@/components/app/app-page-header';
import { AppPagination } from '@/components/app/app-pagination';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import CreateOrUpdateInternamentoModal from '@/pages/Internamentos/CreateOrUpdateInternamentoModal';
import { type BreadcrumbItem } from '@/types';
import type { InternamentoOptions, LookupOption } from '@/types/internamento';
import type { InternamentoItem, Pagination } from '@/types/type';
import { Head, router } from '@inertiajs/react';
import { ClipboardList, Stethoscope } from 'lucide-react';
import { useState } from 'react';

type Props = {
    pendentes: Pagination<InternamentoItem>;
    filters: {
        minhaEquipa?: boolean;
    };
    complicacoesOptions: LookupOption[];
    internamentoOptions: InternamentoOptions;
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

/** Dias corridos desde uma data (string "YYYY-MM-DD") até hoje. */
function diasDesde(data: string | null | undefined): number | null {
    if (!data) {
        return null;
    }

    const inicio = new Date(data);

    if (Number.isNaN(inicio.getTime())) {
        return null;
    }

    return Math.floor((Date.now() - inicio.getTime()) / (1000 * 60 * 60 * 24));
}

export default function Dashboard({ pendentes, filters, complicacoesOptions, internamentoOptions }: Props) {
    const [minhaEquipa, setMinhaEquipa] = useState(Boolean(filters.minhaEquipa));
    const [altaTargetId, setAltaTargetId] = useState<number | null>(null);

    const altaTarget = altaTargetId != null ? (pendentes.data.find((item) => item.id === altaTargetId) ?? null) : null;

    const trocarMinhaEquipa = (checked: boolean) => {
        setMinhaEquipa(checked);
        router.get(route('dashboard'), checked ? { minhaEquipa: '1' } : {}, { preserveState: true, preserveScroll: true, replace: true });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />

            <div className="p-6">
                <AppPageHeader
                    title="Dashboard"
                    description="Doentes operados com a morbilidade cirúrgica aos 30 dias por classificar."
                    action={
                        <label className="flex items-center gap-2 text-sm text-neutral-600 select-none dark:text-neutral-300">
                            <input
                                type="checkbox"
                                checked={minhaEquipa}
                                onChange={(event) => trocarMinhaEquipa(event.target.checked)}
                                className="accent-primary size-4 rounded"
                            />
                            A minha equipa
                        </label>
                    }
                />

                <div className="mb-4 flex items-center gap-2 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800 dark:border-amber-900 dark:bg-amber-950/30 dark:text-amber-300">
                    <ClipboardList className="size-4 shrink-0" />
                    <p>
                        <strong>{pendentes.total}</strong> {pendentes.total === 1 ? 'doente' : 'doentes'} operados cuja data de alta foi há mais de 30 dias
                        e cujas complicações (Clavien-Dindo) ainda não foram classificadas — mesmo que não tenha havido nenhuma, é preciso confirmá-lo.
                    </p>
                </div>

                {pendentes.data.length === 0 ? (
                    <p className="text-muted-foreground">Nenhum doente por classificar.</p>
                ) : (
                    <div className="overflow-hidden rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                        <div className="overflow-x-auto">
                            <table className="w-full text-sm">
                                <thead className="border-b border-neutral-200 bg-neutral-50 dark:border-neutral-800 dark:bg-neutral-950">
                                    <tr>
                                        {['Doente', 'Processo', 'Cama', 'Localização', 'Dias desde a alta', 'Motivo', 'Responsável', 'Ações'].map((label) => (
                                            <th
                                                key={label}
                                                scope="col"
                                                className="px-3 py-3 text-left text-xs font-semibold tracking-wide whitespace-nowrap text-neutral-500 uppercase dark:text-neutral-400"
                                            >
                                                {label}
                                            </th>
                                        ))}
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-neutral-200 dark:divide-neutral-800">
                                    {pendentes.data.map((item) => (
                                        <tr key={item.id} className="hover:bg-neutral-50 dark:hover:bg-neutral-800/40">
                                            <td className="px-3 py-3">
                                                <div className="flex items-center gap-3">
                                                    <AppAvatar name={item.nome_curto ?? '?'} />
                                                    <span className="font-medium text-neutral-900 dark:text-white">{item.id}</span>
                                                </div>
                                            </td>
                                            <td className="px-3 py-3 whitespace-nowrap text-neutral-700 dark:text-neutral-300">{item.numero_processo}</td>
                                            <td className="px-3 py-3 whitespace-nowrap text-neutral-700 dark:text-neutral-300">{item.data_de_saida}</td>
                                            <td className="px-3 py-3 whitespace-nowrap text-neutral-700 dark:text-neutral-300">
                                                {(() => {
                                                    const dias = diasDesde(item.data_de_alta);
                                                    return dias != null ? `${dias} d` : '—';
                                                })()}
                                            </td>
                                            <td className="px-3 py-3 text-neutral-700 dark:text-neutral-300">{item.motivo_internamento}</td>
                                            <td className="px-3 py-3 whitespace-nowrap text-neutral-700 dark:text-neutral-300">{item.responsavel}</td>
                                            <td className="px-3 py-3 whitespace-nowrap">
                                                <Button type="button" size="sm" variant="outline" onClick={() => setAltaTargetId(item.id ?? null)}>
                                                    <Stethoscope className="size-3.5" />
                                                    Classificar
                                                </Button>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>

                        <div className="border-t border-neutral-200 px-4 py-2 dark:border-neutral-800">
                            <AppPagination
                                links={pendentes.links}
                                from={pendentes.from ?? undefined}
                                to={pendentes.to ?? undefined}
                                total={pendentes.total ?? undefined}
                            />
                        </div>
                    </div>
                )}
            </div>

            <CreateOrUpdateInternamentoModal
                open={altaTargetId != null}
                onClose={() => setAltaTargetId(null)}
                internamento={altaTarget}
                utenteId={altaTarget?.utente_id ?? null}
                internamentoOptions={internamentoOptions}
                complicacoesOptions={complicacoesOptions}
                onSubmit={() => {
                    setAltaTargetId(null);
                    router.reload({ only: ['pendentes'] });
                }}
            />
        </AppLayout>
    );
}
