import { AppConfirmDialog } from '@/components/app/app-confirm-dialog';
import { AppPageHeader } from '@/components/app/app-page-header';
import { AppPagination } from '@/components/app/app-pagination';
import { AppTable, AppTableColumn } from '@/components/app/app-table';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import type { Pagination, ResolucaoComplicacaoItem } from '@/types/type';
import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import toast from 'react-hot-toast';
import CreateOrUpdateResolucaoComplicacaoModal from './CreateOrUpdateResolucaoComplicacaoModal';

type Props = {
    resolucoesComplicacao: Pagination<ResolucaoComplicacaoItem>;
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Resoluções de Complicação',
        href: '/resolucoes-complicacao',
    },
];

export default function ResolucoesComplicacaoIndex({ resolucoesComplicacao }: Props) {
    const [openModal, setOpenModal] = useState(false);
    const [editing, setEditing] = useState<ResolucaoComplicacaoItem | null>(null);
    const [deleting, setDeleting] = useState<ResolucaoComplicacaoItem | null>(null);
    const [deletingLoading, setDeletingLoading] = useState(false);

    const confirmDelete = () => {
        if (!deleting) {
            return;
        }

        setDeletingLoading(true);

        router.delete(route('resolucoes-complicacao.destroy', deleting.id), {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Resolução removida com sucesso.');
                setDeleting(null);
            },
            onError: () => toast.error('Erro ao remover a resolução.'),
            onFinish: () => setDeletingLoading(false),
        });
    };

    const columns: AppTableColumn<ResolucaoComplicacaoItem>[] = [
        {
            key: 'nome',
            label: 'Nome',
        },
        {
            key: 'acoes',
            label: 'Ações',
            className: 'text-right',
            render: (resolucao) => (
                <div className="flex justify-end gap-2">
                    <Button
                        size="sm"
                        onClick={() => {
                            setEditing(resolucao);
                            setOpenModal(true);
                        }}
                    >
                        Editar
                    </Button>

                    <Button size="sm" variant="destructive" onClick={() => setDeleting(resolucao)}>
                        Eliminar
                    </Button>
                </div>
            ),
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Resoluções de Complicação" />

            <div className="p-6">
                <AppPageHeader
                    title="Resoluções de Complicação"
                    description="Lista de resoluções possíveis para as complicações registadas num internamento."
                    action={
                        <Button
                            onClick={() => {
                                setEditing(null);
                                setOpenModal(true);
                            }}
                        >
                            Nova resolução
                        </Button>
                    }
                />

                <AppTable columns={columns} data={resolucoesComplicacao.data} rowKey={(resolucao) => resolucao.id} />

                <AppPagination
                    links={resolucoesComplicacao.links}
                    from={resolucoesComplicacao.from ?? undefined}
                    to={resolucoesComplicacao.to ?? undefined}
                    total={resolucoesComplicacao.total ?? undefined}
                />
            </div>

            <CreateOrUpdateResolucaoComplicacaoModal open={openModal} onClose={() => setOpenModal(false)} resolucaoComplicacao={editing} />

            <AppConfirmDialog
                open={!!deleting}
                title="Eliminar resolução"
                description={`Tem a certeza que quer eliminar "${deleting?.nome}"? Esta ação não pode ser desfeita.`}
                onConfirm={confirmDelete}
                onClose={() => setDeleting(null)}
                loading={deletingLoading}
            />
        </AppLayout>
    );
}
