import { router } from '@inertiajs/react';
import { FormEvent, useState } from 'react';

interface UseServerSearchOptions {
    url: string;
    initialSearch?: string;
    /** Parâmetros adicionais sempre enviados no pedido (ex: utente_id). */
    extraParams?: Record<string, unknown>;
}

/**
 * Centraliza o padrão repetido de pesquisa filtrada no servidor via Inertia:
 * estado de pesquisa/loading e router.get com preserveState/preserveScroll/replace.
 */
export function useServerSearch({ url, initialSearch = '', extraParams = {} }: UseServerSearchOptions) {
    const [search, setSearch] = useState(initialSearch);
    const [searching, setSearching] = useState(false);

    const request = (params: Record<string, unknown>) => {
        setSearching(true);

        router.get(url, params, {
            preserveState: true,
            preserveScroll: true,
            replace: true,

            onFinish: () => {
                setSearching(false);
            },
        });
    };

    const handleSearch = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        request({ ...extraParams, search: search.trim() });
    };

    const handleReset = () => {
        setSearch('');

        request({ ...extraParams });
    };

    return { search, setSearch, searching, handleSearch, handleReset };
}
