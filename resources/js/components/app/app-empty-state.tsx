import { ReactNode } from 'react';
import { Button } from '@/components/ui/button';

interface AppEmptyStateProps {
    title: string;
    description?: string;
    action?: {
        label: string;
        onClick: () => void;
    };
    children?: ReactNode;
}

/** Mensagem padronizada para listas/resultados vazios, com ação opcional. */
export function AppEmptyState({ title, description, action, children }: AppEmptyStateProps) {
    return (
        <div className="rounded-xl border border-dashed border-neutral-300 p-10 text-center dark:border-neutral-700">
            <p className="text-sm font-medium text-neutral-700 dark:text-neutral-300">{title}</p>

            {description && <p className="mt-1 text-sm text-neutral-500 dark:text-neutral-400">{description}</p>}

            {children}

            {action && (
                <Button type="button" className="mt-4" onClick={action.onClick}>
                    {action.label}
                </Button>
            )}
        </div>
    );
}
