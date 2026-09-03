import type { ReactNode } from 'react';
import { Button } from '@/components/ui/button';

interface AppEmptyStateAction {
    label: string;
    onClick: () => void;
    variant?: 'default' | 'destructive' | 'outline' | 'secondary' | 'ghost' | 'link' | 'success' | 'warning' | 'info' | 'muted';
}

interface AppEmptyStateProps {
    title: string;
    description?: string;
    actions?: AppEmptyStateAction[];
    children?: ReactNode;
}

/**
 * Mensagem padronizada para listas ou resultados vazios.
 */
export function AppEmptyState({
    title,
    description,
    actions,
    children,
}: AppEmptyStateProps) {
    return (
        <div className="rounded-xl border border-dashed border-neutral-300 p-10 text-center dark:border-neutral-700">
            <p className="text-xl font-medium text-neutral-700 dark:text-neutral-300">
                {title}
            </p>

            {description && (
                <p className="mt-1 text-lg text-neutral-500 dark:text-neutral-400">
                    {description}
                </p>
            )}

            {children}

            {actions && actions.length > 0 && (
                <div
                    className={
                        actions.length === 1 ? 'mt-4 flex justify-end' : 'mt-4 flex justify-between'
                    }
                >
                    {actions.map((action, index) => (
                        <Button
                            key={index}
                            type="button"
                            onClick={action.onClick}
                            variant={action.variant}
                        >
                            {action.label}
                        </Button>
                    ))}
                </div>
            )}
        </div>
    );
}
