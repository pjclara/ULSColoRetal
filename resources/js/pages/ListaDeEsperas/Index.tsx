import { AppFilters } from '@/components/app/app-filters';
import { AppPageHeader } from '@/components/app/app-page-header';
import { AppPagination } from '@/components/app/app-pagination';
import { AppTable, AppTableColumn } from '@/components/app/app-table';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { ListaDeEsperaItem, Option } from '@/types/type';
import { Head, router } from '@inertiajs/react';
import { AgendamentoItem } from '@/types/type';
import { useState } from 'react';

import CreateOrUpdateAgendamento from '../Agendamentos/CreateOrUpdateAgendamento';
import CreateOrUpdateListaDeEspera from './CreateOrUpdateListaDeEspera';

type PaginationLink = {
    url: string | null;
    label: string;
    active: boolean;
};

type Props = {
    listaDeEsperas: {
        data: ListaDeEsperaItem[];
        links: PaginationLink[];
        from: number;
        to: number;
        total: number;
    };
    filters: {
        search?: string;
    };
    estadoOptions: Option[];
    estadoDeAgendamentoOptions: Option[];
    responsavelOptions: Option[];
    diagnosticosOptions: Option[];
    salaDeAgendamentoOptions: Option[];
    tipoDeAgendamentoOptions: Option[];
    localDeAgendamentoOptions: Option[];
    
};

const breadcrumbs = [
    {
        title: 'Lista de Espera',
        href: route('lista-de-esperas.index'),
    },
];

export default function Index({
    listaDeEsperas,
    filters,
    estadoOptions,
    responsavelOptions,
    diagnosticosOptions,
    estadoDeAgendamentoOptions,
    salaDeAgendamentoOptions,
    localDeAgendamentoOptions,
    tipoDeAgendamentoOptions,
}: Props) {
    // Modal da Lista de Espera
    const [isOpen, setIsOpen] = useState(false);
    const [listaDeEspera, setListaDeEspera] =
        useState<ListaDeEsperaItem | null>(null);

    // Modal de Agendamento
    const [isOpenAgendamento, setIsOpenAgendamento] = useState(false);
    const [selectedListaDeEspera, setSelectedListaDeEspera] =
        useState<ListaDeEsperaItem | null>(null);
    const [selectedAgendamento, setSelectedAgendamento] =
        useState<AgendamentoItem | null>(null);

    const [filtroNomeOuProcesso, setFiltroNomeOuProcesso] = useState(
        filters.search ?? '',
    );

    /**
     * Abre o modal para editar a lista de espera.
     */
    const openEdit = (item: ListaDeEsperaItem) => {
        setListaDeEspera(item);
        setIsOpen(true);
    };

    /**
     * Fecha o modal da lista de espera.
     */
    const closeModal = () => {
        setIsOpen(false);
        setListaDeEspera(null);
    };

    /**
     * Abre o modal de agendamento.
     */
    const openAgendamento = (item: AgendamentoItem) => {
        setSelectedAgendamento(item);
        setIsOpenAgendamento(true);
    };

    /**
     * Fecha o modal de agendamento.
     */
    const closeAgendamentoModal = () => {
        setIsOpenAgendamento(false);
        setSelectedAgendamento(null);
    };

    const applyFilters = () => {
        const search = filtroNomeOuProcesso.trim();

        router.get(
            route('lista-de-esperas.index'),
            search ? { search } : {},
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            },
        );
    };

    const resetFilters = () => {
        setFiltroNomeOuProcesso('');

        router.get(
            route('lista-de-esperas.index'),
            {},
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            },
        );
    };

    const columns: AppTableColumn<ListaDeEsperaItem>[] = [
        {
            label: 'Nome',
            key: 'nome',
        },
        {
            label: 'Número de Processo',
            key: 'numero_processo',
        },
        {
            label: 'Diagnósticos',
            key: 'diagnosticos',
            render: (item) =>
                item.diagnosticos?.length
                    ? item.diagnosticos
                          .map((diagnostico) => diagnostico.nome)
                          .join(', ')
                    : '-',
        },
        {
            label: 'Responsável',
            key: 'responsavel_nome',
            render: (item) => item.responsavel?.abrev ?? '-',
        },
        {
            label: 'Ações',
            key: 'actions',
            render: (item) => (
                <Button
                    type="button"
                    size="sm"
                    variant="ghost"
                    onClick={() => openEdit(item)}
                >
                    Editar
                </Button>
            ),
        },
        {
            label: 'Agendamentos',
            key: 'agendamentos',
            render: (item) => {
                const temAgendamento = item.agendamentos?.some(
                    (agendamento) => agendamento.start,
                );

                return temAgendamento ? (
                    <Button
                        type="button"
                        size="lg"
                        variant="success"
                        onClick={() => openAgendamento(item)}
                    >
                        Ver
                    </Button>
                ) : (
                    <Button
                        type="button"
                        size="lg"
                        variant="outline"
                        onClick={() => openAgendamento(item)}
                    >
                        Agendar
                    </Button>
                );
            },
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Lista de Espera" />

            <div className="p-6">
                <AppPageHeader
                    title="Lista de Espera"
                    description="Gestão da lista de espera dos pacientes."
                    action={
                        <Button
                            type="button"
                            size="sm"
                            onClick={() =>
                                router.get(
                                    route('lista-de-esperas.create'),
                                )
                            }
                        >
                            Novo registo
                        </Button>
                    }
                />

                <AppFilters
                    onSubmit={(event) => {
                        event.preventDefault();
                        applyFilters();
                    }}
                    onReset={resetFilters}
                >
                    <div className="w-full md:w-80">
                        <input
                            type="text"
                            value={filtroNomeOuProcesso}
                            onChange={(event) =>
                                setFiltroNomeOuProcesso(event.target.value)
                            }
                            placeholder="Filtrar por nome ou processo"
                            className="border-input bg-background ring-offset-background placeholder:text-muted-foreground focus-visible:ring-ring w-full rounded-md border px-3 py-2 text-sm focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:outline-none"
                        />
                    </div>
                </AppFilters>

                <div className="mt-6">
                    {listaDeEsperas.data.length === 0 ? (
                        <p className="text-muted-foreground">
                            Nenhum registo encontrado.
                        </p>
                    ) : (
                        <>
                            <AppTable
                                columns={columns}
                                data={listaDeEsperas.data}
                            />

                            <AppPagination
                                links={listaDeEsperas.links}
                                from={listaDeEsperas.from}
                                to={listaDeEsperas.to}
                                total={listaDeEsperas.total}
                            />
                        </>
                    )}
                </div>
            </div>

            {/* Modal para editar a Lista de Espera */}
            <CreateOrUpdateListaDeEspera
                listaDeEspera={listaDeEspera}
                isOpenListaDeEspera={isOpen}
                onClose={closeModal}
                onSuccess={closeModal}
                estadoOptions={estadoOptions}
                responsavelOptions={responsavelOptions}
                diagnosticosOptions={diagnosticosOptions}
                utenteId={listaDeEspera?.utente_id ?? null}
            />

            {/* Modal para criar/ver Agendamento */}
            <CreateOrUpdateAgendamento
                agendamento={selectedAgendamento ?? null}
                isOpenAgendamento={isOpenAgendamento}
                onClose={closeAgendamentoModal}
                onSuccess={closeAgendamentoModal}
                listaDeEsperaId={selectedListaDeEspera?.id}
                responsavelOptions={responsavelOptions}
                tipoDeAgendamentoOptions={tipoDeAgendamentoOptions}
                localDeAgendamentoOptions={localDeAgendamentoOptions}
                salaDeAgendamentoOptions={salaDeAgendamentoOptions}
                estadoDeAgendamentoOptions={estadoDeAgendamentoOptions}
            />
        </AppLayout>
    );
}
