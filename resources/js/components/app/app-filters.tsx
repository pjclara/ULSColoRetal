import { ReactNode, FormEvent } from 'react';
import { Button } from '@/components/ui/button';

interface AppFiltersProps {
    children: ReactNode;
    onSubmit: (event: FormEvent<HTMLFormElement>) => void;
    onReset?: () => void;
    loading?: boolean;
}

export function AppFilters({
    children,
    onSubmit,
    onReset,
    loading = false,
}: AppFiltersProps) {
    return (
        <form
            onSubmit={onSubmit}
            className="mb-6 rounded-xl border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-800 dark:bg-neutral-900"
        >
            <div className="flex flex-col gap-4">
                <div className="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                    {children}
                </div>

                <div className="flex items-center justify-end gap-2 border-t border-neutral-200 pt-4 dark:border-neutral-800">
                    {onReset && (
                        <Button
                            type="button"
                            variant="outline"
                            onClick={onReset}
                            disabled={loading}
                        >
                            Limpar
                        </Button>
                    )}

                    <Button
                        type="submit"
                        disabled={loading}
                    >
                        {loading ? 'A pesquisar...' : 'Pesquisar'}
                    </Button>
                </div>
            </div>
        </form>
    );
}