
import { FormEvent, useState } from 'react';
import { router } from '@inertiajs/react';

import { AppEmptyState } from '@/components/app/app-empty-state';
import { AppFilters } from '@/components/app/app-filters';
import { AppFormField } from '@/components/app/app-form-field';
import { AppPagination } from '@/components/app/app-pagination';
import { AppTable, AppTableColumn } from '@/components/app/app-table';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { useServerSearch } from '@/hooks/use-server-search';

import type { UtenteItem } from '@/types/type';

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
};

export function StepUtente({
    utentes,
    filters,
    onSelect,
    url,
}: Props) {
    const { search, setSearch, searching, handleSearch, handleReset } = useServerSearch({
        url,
        initialSearch: filters.search ?? '',
    });

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
            key: 'data_nascimento',
            label: 'Data de nascimento',
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
            <div>
                <h2 className="text-xl font-semibold">
                    Selecionar utente
                </h2>

                <p className="mt-1 text-sm text-neutral-500">
                    Pesquise pelo nome ou número de processo.
                </p>
            </div>

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
                    description="Altere os critérios de pesquisa e tente novamente."
                />
            ) : (
                <>
                    <AppTable
                        columns={columns}
                        data={utentesList}
                        rowKey={(utente) => utente.id}
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
        </div>
    );
}

