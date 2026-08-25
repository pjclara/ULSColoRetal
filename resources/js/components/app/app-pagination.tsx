import { router } from '@inertiajs/react';

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface AppPaginationProps {
    links: PaginationLink[];
    from?: number | null;
    to?: number | null;
    total?: number;
}

export function AppPagination({
    links,
    from,
    to,
    total,
}: AppPaginationProps) {
    if (!links || links.length <= 3) {
        return null;
    }

    const navigate = (url: string | null) => {
        if (!url) {
            return;
        }

        router.get(
            url,
            {},
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            },
        );
    };

    return (
        <div className="mt-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div className="text-sm text-neutral-500 dark:text-neutral-400">
                {from && to && total !== undefined ? (
                    <>
                        A mostrar <strong>{from}</strong> a{' '}
                        <strong>{to}</strong> de{' '}
                        <strong>{total}</strong> registos
                    </>
                ) : null}
            </div>

            <nav
                className="flex items-center gap-1"
                aria-label="Paginação"
            >
                {links.map((link, index) => {
                    const isPrevious = index === 0;
                    const isNext = index === links.length - 1;

                    return (
                        <button
                            key={`${link.label}-${index}`}
                            type="button"
                            disabled={!link.url || link.active}
                            onClick={() => navigate(link.url)}
                            aria-current={
                                link.active ? 'page' : undefined
                            }
                            className={`
                                min-w-9 rounded-md px-3 py-2 text-sm transition
                                ${
                                    link.active
                                        ? 'bg-blue-600 text-white'
                                        : link.url
                                          ? 'border border-neutral-200 bg-white text-neutral-700 hover:bg-neutral-100 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800'
                                          : 'cursor-not-allowed text-neutral-300 dark:text-neutral-700'
                                }
                            `}
                        >
                            {isPrevious
                                ? '‹'
                                : isNext
                                  ? '›'
                                  : link.label}
                        </button>
                    );
                })}
            </nav>
        </div>
    );
}