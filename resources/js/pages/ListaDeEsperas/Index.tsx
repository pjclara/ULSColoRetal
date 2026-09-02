import { AppPageHeader } from '@/components/app/app-page-header';
import { AppTable, AppTableColumn } from '@/components/app/app-table';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { Head, router } from '@inertiajs/react';

type Diagnostico = {
    id: number;
    nome: string;
};

type ListaDeEsperaItem = {
    id: number;
    nome: string;
    utente_id: number;
    numero_processo: string;
    diagnosticos: Diagnostico[];
    responsavel_id: number | null;
    responsavel_nome: string | null;
};

type Props = {
    listaDeEsperas: {
        data: ListaDeEsperaItem[];
    };
};

const columns: AppTableColumn<ListaDeEsperaItem>[] = [
    { label: 'ID', key: 'id' },

    { label: 'Nome', key: 'nome' },
    { label: 'Número de Processo', key: 'numero_processo' },
    { label: 'Diagnósticos', key: 'diagnosticos', render: (item: ListaDeEsperaItem) => item.diagnosticos.map((d) => d.nome).join(', ') },
    { label: 'Responsável', key: 'responsavel_nome', render: (item: ListaDeEsperaItem) => item.responsavel_nome ?? '-' },
];

const breadcrumbs = [
    {
        title: 'Lista de Espera',
        href: route('lista-de-esperas.index'),
    },
];

export default function Index({ listaDeEsperas }: Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="ListaDeEsperas" />

            <div className="p-6">
                <AppPageHeader
                    title="Lista de Espera"
                    description="Gestão da lista de espera dos pacientes."
                    action={
                        <Button type="button" size="sm" onClick={() => router.get(route('lista-de-esperas.create'))}>
                            Novo registo
                        </Button>
                    }
                />

                <div className="mt-6">
                    {listaDeEsperas.data.length === 0 ? <p>Nenhum registo encontrado.</p> : <AppTable columns={columns} data={listaDeEsperas.data} />}
                </div>
            </div>
        </AppLayout>
    );
}
