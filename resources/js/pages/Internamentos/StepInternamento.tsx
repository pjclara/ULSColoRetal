import { router } from '@inertiajs/react';
import { FormEvent, useState } from 'react';

import { AppEmptyState } from '@/components/app/app-empty-state';
import { AppEntitySummary } from '@/components/app/app-entity-summary';
import { AppFilters } from '@/components/app/app-filters';
import { AppFormField } from '@/components/app/app-form-field';
import { AppPagination } from '@/components/app/app-pagination';
import { AppTable, AppTableColumn } from '@/components/app/app-table';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';

import type { InternamentoItem, Option, User, UtenteItem } from '@/types/type';
import CreateOrUpdateInternamentoModal from './CreateOrUpdateInternamentoModal';

type Pagination<T> = {
    data: T[];
    links: {
        url: string | null;
        label: string;
        active: boolean;
    }[];
    from?: number | null;
    to?: number | null;
    total?: number | null;
};

type Props = {
    utente: UtenteItem;

    internamentos: InternamentoItem[] | Pagination<InternamentoItem>;

    filters: {
        search?: string;
    };

    url: string;

    onSelect: (internamento: InternamentoItem) => void;

    onBack: () => void;

    onContinue: () => void;

    selectedInternamento?: InternamentoItem | null;

    onCreate?: () => void;
    origensInternamento: Option[];
    estadosAlta: Option[];
    responsaveis: User[];
    clavienDindo: Option[];
    destinos: Option[];
    casosSociais: Option[];
};

export function StepInternamento({
    utente,
    internamentos,
    filters,
    url,
    onSelect,
    onBack,
    onContinue,
    selectedInternamento = null,
    onCreate,
    origensInternamento,
    estadosAlta,
    responsaveis,
    clavienDindo,
    destinos,
    casosSociais,
}: Props) {
    const [search, setSearch] = useState(filters.search ?? '');
    const [searching, setSearching] = useState(false);
    const [showInternamentoModal, setShowInternamentoModal] = useState(false);

    /**
     * Os internamentos já vêm filtrados
     * pelo backend.
     */
    const internamentosList = Array.isArray(internamentos) ? internamentos : internamentos.data;

    /**
     * Pesquisa os internamentos do utente
     * no backend.
     */
    const handleSearch = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        setSearching(true);

        router.get(
            url,
            {
                utente_id: utente.id,
                search: search.trim(),
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,

                onFinish: () => {
                    setSearching(false);
                },
            },
        );
    };

    /**
     * Limpar pesquisa.
     */
    const handleReset = () => {
        setSearch('');
        setSearching(true);

        router.get(
            url,
            {
                utente_id: utente.id,
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,

                onFinish: () => {
                    setSearching(false);
                },
            },
        );
    };

    const columns: AppTableColumn<InternamentoItem>[] = [
        {
            key: 'id',
            label: '#',
        },

        {
            key: 'data_de_entrada',
            label: 'Data Entrada',
        },

        {
            key: 'data_de_saida',
            label: 'Data Saída',
        },

        {
            key: 'estado_da_alta_id',
            label: 'Estado',
        },

        {
            key: 'responsavel',
            label: 'Responsavel',
        },

        {
            key: 'actions',
            label: 'Ações',
            className: 'text-right',

            render: (internamento) => {
                const selected = selectedInternamento?.id === internamento.id;

                return (
                    <div className="flex justify-end gap-2">
                        <Button type="button" size="sm" variant={selected ? 'default' : 'outline'} onClick={() => onSelect(internamento)}>
                            {selected ? 'Selecionado' : 'Selecionar'}
                        </Button>
                    </div>
                );
            },
        },
    ];

    return (
        <div className="space-y-6">
            <div>
                <h2 className="text-xl font-semibold">Selecionar internamento</h2>

                <p className="mt-1 text-sm text-neutral-500">Selecione um internamento existente para o utente ou crie um novo.</p>
            </div>

            <AppEntitySummary
                title="Utente selecionado"
                fields={[
                    {
                        label: 'Nome',
                        value: utente.nome,
                    },
                    {
                        label: 'N.º Processo',
                        value: utente.numero_processo ?? '',
                    },
                    {
                        label: 'Data de nascimento',
                        value: utente.data_nascimento ?? '',
                    },
                ]}
                action={
                    <Button type="button" variant="outline" onClick={onBack}>
                        Alterar utente
                    </Button>
                }
            />


            {internamentosList.length === 0 ? (
                <AppEmptyState
                    title="Nenhum internamento encontrado."
                    description="Este utente não possui internamentos que correspondam à pesquisa."
                    action={
                        onCreate
                            ? {
                                  label: 'Criar internamento',
                                  onClick: onCreate,
                              }
                            : undefined
                    }
                />
            ) : (
                <>
                    <AppTable columns={columns} data={internamentosList} rowKey={(internamento) => internamento.id} />

                    {!Array.isArray(internamentos) && (
                        <AppPagination
                            links={internamentos.links}
                            from={internamentos.from ?? undefined}
                            to={internamentos.to ?? undefined}
                            total={internamentos.total ?? undefined}
                        />
                    )}
                </>
            )}

            <div className="flex justify-between border-t border-neutral-200 pt-5 dark:border-neutral-800">
                <Button type="button" variant="outline" onClick={onBack}>
                    Voltar
                </Button>

                <div className="flex gap-3">
                    {onCreate && (
                        <Button type="button" variant="outline" onClick={onCreate}>
                            Novo internamento
                        </Button>
                    )}

                    <Button type="button" disabled={!selectedInternamento} onClick={onContinue}>
                        Continuar
                    </Button>
                </div>
            </div>
            <CreateOrUpdateInternamentoModal
                open={showInternamentoModal}
                onClose={() => setShowInternamentoModal(false)}
                utenteId={utente.id}
                origensInternamento={origensInternamento}
                estadosAlta={estadosAlta}
                responsaveis={responsaveis}
                clavienDindo={clavienDindo}
                destinos={destinos}
                casosSociais={casosSociais}
            />
        </div>
    );
}
