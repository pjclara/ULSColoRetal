import { AppPageHeader } from '@/components/app/app-page-header';
import { AppPagination } from '@/components/app/app-pagination';
import { AppTable, AppTableColumn } from '@/components/app/app-table';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { InternamentoItem } from '@/types/type';
import { Head, router } from '@inertiajs/react';

const breadcrumbs = [
    {
        title: 'Internamentos',
        href: route('internamentos.index'),
    },
];

const columns: AppTableColumn<InternamentoItem>[] = [
    {
        label: 'Proeceso',
        key: 'numero_processo',
    },
    {
        label: 'Nome do Paciente',
        key: 'nome_paciente',
    },
    {
        label: 'Data de Internamento',
        key: 'data_internamento',
    },
    {
        label: 'Cama',
        key: 'cama',
    },
    {
        label: 'Responsável',
        key: 'responsavel',
    }
    
];

type Props = {
    internamentos: {
        data: InternamentoItem[];
        links: {
            url: string | null;
            label: string;
            active: boolean;
        }[];
        from: number | null;
        to: number | null;
        total: number | null;
    };
};

export default function Index({ internamentos }: Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Internamentos" />

            <div className="p-6">
                <AppPageHeader
                    title="Internamentos"
                    description="Gestão dos internamentos dos pacientes."
                    action={
                        <Button type="button" size="sm" onClick={() => router.get('/internamentos/create')}>
                            Novo internamento
                        </Button>
                    }
                />

                <AppTable
                    columns={columns}
                    data={internamentos.data}
                   
                    
                />
                <AppPagination
                    links={internamentos.links}
                    from={internamentos.from ?? undefined}
                    to={internamentos.to ?? undefined}
                    total={internamentos.total ?? undefined}
                />
            </div>
        </AppLayout>
    );
}
