import { AppPageHeader } from '@/components/app/app-page-header';
import { AppPagination } from '@/components/app/app-pagination';
import { AppTable, AppTableColumn } from '@/components/app/app-table';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import type { InternamentoOptions } from '@/types/internamento';
import { InternamentoItem, Pagination } from '@/types/type';
import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import CreateOrUpdateInternamentoModal from './CreateOrUpdateInternamentoModal';

const breadcrumbs = [
    {
        title: 'Internamentos',
        href: route('internamentos.index'),
    },
];

type Props = {
    internamentos: Pagination<InternamentoItem>;

    internamentoOptions: InternamentoOptions;
};

export default function Index({ internamentos, internamentoOptions }: Props) {
    const [showInternamentoModal, setShowInternamentoModal] = useState(false);

    const [selectedInternamento, setSelectedInternamento] = useState<InternamentoItem | null>(null);

    const openInternamento = (internamento: InternamentoItem) => {
        setSelectedInternamento(internamento);
        setShowInternamentoModal(true);
    };

    const closeInternamento = () => {
        setShowInternamentoModal(false);
        setSelectedInternamento(null);
    };

    const formatDate = (date: string) => {
        if (!date) return '-';

        return new Intl.DateTimeFormat('pt-PT', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
        }).format(new Date(date));
    };

    const columns: AppTableColumn<InternamentoItem>[] = [
        {
            label: 'Utente',
            key: 'nome_curto',
        },
        {
            label: 'Processo',
            key: 'numero_processo',
            render: (item) => <span className="font-medium text-neutral-700 dark:text-neutral-300">#{item.numero_processo}</span>,
        },
        {
            label: 'Data de entrada',
            key: 'data_de_entrada',
            render: (internamento) => formatDate(internamento.data_de_entrada),
        },

        {
            label: 'Diagnósticos',
            key: 'diagnosticos',
            render: (item) => (
                <div className="flex flex-wrap gap-1.5">
                    {item.diagnosticos?.length ? (
                        item.diagnosticos.map((diagnostico) => (
                            <span
                                key={diagnostico.id}
                                className="rounded-md bg-neutral-100 px-2 py-1 text-xs font-medium text-neutral-600 dark:bg-neutral-800 dark:text-neutral-300"
                            >
                                {diagnostico.nome}
                            </span>
                        ))
                    ) : (
                        <span className="text-neutral-400">—</span>
                    )}
                </div>
            ),
        },
        {
            label: 'Responsável',
            key: 'responsavel',
        },
        {
            label: 'Ações',
            key: 'actions',
            render: (internamento) => (
                <div className="flex justify-end gap-2">
                    <div className="flex items-center justify-end gap-2">
                        <Button type="button" size="sm" onClick={() => openInternamento(internamento)}>
                            Editar
                        </Button>
                    </div>
                </div>
            ),
        },
        {
            label: 'Motivo',
            key: 'motivo_internamento',
            fullRow: true,
            render: (item) => (
                <div className="flex items-center gap-2">
                    <span className="text-xs font-semibold tracking-wide text-neutral-400 uppercase">Motivo</span>

                    <span className="text-sm text-neutral-600 dark:text-neutral-400">{item.motivo_internamento || 'Sem motivo registado'}</span>
                </div>
            ),
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Internamentos" />

            <div className="p-6">
                <AppPageHeader
                    title="Internamentos"
                    description="Gestão dos internamentos dos pacientes."
                    action={
                        <Button type="button" size="sm" onClick={() => router.get(route('internamentos.create'))}>
                            Novo internamento
                        </Button>
                    }
                />

                <AppTable columns={columns} data={internamentos.data} rowKey={(internamento, index) => internamento.id ?? index} />

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
                    />
                )}
            </div>
        </AppLayout>
    );
}
