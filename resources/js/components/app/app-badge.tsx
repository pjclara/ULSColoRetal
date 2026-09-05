import { cn } from '@/lib/utils';
import { ReactNode } from 'react';

export type AppBadgeTone = 'success' | 'warning' | 'info' | 'danger' | 'neutral';

const toneClasses: Record<AppBadgeTone, string> = {
    success: 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400',
    warning: 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
    info: 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400',
    danger: 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400',
    neutral: 'bg-neutral-100 text-neutral-600 dark:bg-neutral-800 dark:text-neutral-300',
};

const dotClasses: Record<AppBadgeTone, string> = {
    success: 'bg-green-500',
    warning: 'bg-amber-500',
    info: 'bg-blue-500',
    danger: 'bg-red-500',
    neutral: 'bg-neutral-400',
};

type AppBadgeProps = {
    tone?: AppBadgeTone;
    children: ReactNode;
    className?: string;
};

/** Pill de estado com ponto colorido, para usar em tabelas/listas (ex: estado de lista de espera, agendamento). */
export function AppBadge({ tone = 'neutral', children, className }: AppBadgeProps) {
    return (
        <span className={cn('inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium whitespace-nowrap', toneClasses[tone], className)}>
            <span className={cn('size-1.5 shrink-0 rounded-full', dotClasses[tone])} />
            {children}
        </span>
    );
}
