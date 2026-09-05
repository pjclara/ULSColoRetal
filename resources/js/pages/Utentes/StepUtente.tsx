
import { AppEmptyState } from '@/components/app/app-empty-state';
import { AppEntitySummary } from '@/components/app/app-entity-summary';
import { AppFilters } from '@/components/app/app-filters';
import { AppFormField } from '@/components/app/app-form-field';
import { AppPagination } from '@/components/app/app-pagination';
import { AppTable, AppTableColumn } from '@/components/app/app-table';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { useServerSearch } from '@/hooks/use-server-search';
import { useState } from 'react';

import type { Option, UtenteItem } from '@/types/type';
import CreateOrUpdateUtente from './CreateOrUpdateUtente';

type Props = {
    utentes:
        | UtenteItem[]
        | {
              data: UtenteItem[];
              links: {
                  url: string | null;
                  label: string;
                  active: boolean;
              }[];
              from?: number | null;
              to?: number | null;
              total?: number | null;
          };

    filters: {
        search?: string;
    };

    onSelect: (utente: UtenteItem) => void;

    selectedUtente?: UtenteItem | null;

    onCreate?: (utente: UtenteItem) => void;

    onContinue?: () => void;
    url: string;
    concelhoOptions: Option[];
};

export function StepUtente({
    utentes,
    filters,
    onSelect,
    selectedUtente,
    onCreate,
    onContinue,
    url,
    concelhoOptions,
}: Props) {
    const { search, setSearch, searching, handleSearch, handleReset } = useServerSearch({
        url,
        initialSearch: filters.search ?? '',
    });

    const [showCreateModal, setShowCreateModal] = useState(false);

    const handleUtenteCriado = (utente: UtenteItem) => {
        setShowCreateModal(false);
        onCreate ? onCreate(utente) : onSelect(utente);
    };

    /**
     * Os resultados já vêm filtrados do backend.
     */
    const utentesList = Array.isArray(utentes)
        ? utentes
        : utentes.data;

    const columns: AppTableColumn<UtenteItem>[] = [
        {
            key: 'nome',
            label: 'Nome',
        },
        {
            key: 'numero_processo',
            label: 'N.º Processo',
        },
        {
            key: 'idade',
            label: 'Idade',
        },
        {
            key: 'actions',
            label: 'Ações',
            className: 'text-right',

            render: (utente) => (
                <div className="flex justify-end">
                    <Button
                        type="button"
                        size="sm"
                        onClick={() => onSelect(utente)}
                    >
                        Selecionar
                    </Button>
                </div>
            ),
        },
    ];

    return (
        <div className="space-y-6">
            <div className="flex items-start justify-between gap-4">
                <div>
                    <h2 className="text-xl font-semibold">
                        Selecionar utente
                    </h2>

                    <p className="mt-1 text-sm text-neutral-500">
                        Pesquise pelo nome ou número de processo. Se o utente não existir, pode criá-lo de imediato.
                    </p>
                </div>

                <Button type="button" variant="info" onClick={() => setShowCreateModal(true)}>
                    Criar novo utente
                </Button>
            </div>

            {selectedUtente && (
                <AppEntitySummary
                    title="Utente selecionado"
                    fields={[
                        { label: 'Nome', value: selectedUtente.nome },
                        { label: 'N.º Processo', value: selectedUtente.numero_processo ?? '' },
                    ]}
                    action={
                        onContinue && (
                            <div className="mt-4 flex shrink-0 justify-end gap-2">
                                <Button type="button" onClick={onContinue}>
                                    Continuar com este utente
                                </Button>
                            </div>
                        )
                    }
                />
            )}

            <AppFilters
                onSubmit={handleSearch}
                onReset={handleReset}
                loading={searching}
            >
                <AppFormField label="Pesquisar">
                    <Input
                        value={search}
                        onChange={(event) =>
                            setSearch(event.target.value)
                        }
                        placeholder="Nome ou número de processo"
                    />
                </AppFormField>
            </AppFilters>

            {utentesList.length === 0 ? (
                <AppEmptyState
                    title="Nenhum utente encontrado."
                    description="Altere os critérios de pesquisa ou crie um novo utente."
                    actions={[
                        {
                            label: 'Criar novo utente',
                            onClick: () => setShowCreateModal(true),
                        },
                    ]}
                />
            ) : (
                <>
                    <AppTable
                        columns={columns}
                        data={utentesList}
                    />

                    {!Array.isArray(utentes) && (
                        <AppPagination
                            links={utentes.links}
                            from={utentes.from ?? undefined}
                            to={utentes.to ?? undefined}
                            total={
                                utentes.total ??
                                undefined
                            }
                        />
                    )}
                </>
            )}

            <CreateOrUpdateUtente
                isOpenUtente={showCreateModal}
                onClose={() => setShowCreateModal(false)}
                onSubmit={handleUtenteCriado}
                concelhoOptions={concelhoOptions}
            />
        </div>
    );
}

