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

    const columns: AppTableColumn<InternamentoItem>[] = [
        {
            label: 'Utente',
            key: 'nome_curto',
        },
        {
            label: 'Processo',
            key: 'numero_processo',
        },
        {
            label: 'Data de entrada',
            key: 'data_de_entrada',
        },
        {
            label: 'Motivo',
            key: 'motivo_internamento',
        },
        {
            label: 'Diagnósticos',
            key: 'diagnosticos',
            render: (internamento) => (
                <ul className="list-inside list-disc text-sm text-gray-600">
                    {internamento?.diagnosticos?.map((diagnostico) => (
                        <li key={diagnostico.id}>{diagnostico.nome}</li>
                    ))}
                </ul>
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
                    <Button type="button" size="sm" variant="outline" onClick={() => router.get(route('internamentos.show', internamento.id))}>
                        Ver detalhes
                    </Button>

                    <Button type="button" size="sm" onClick={() => openInternamento(internamento)}>
                        Editar
                    </Button>
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
