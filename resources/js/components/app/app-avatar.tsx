import { cn } from '@/lib/utils';

const PALETTE = [
    'bg-indigo-100 text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-300',
    'bg-rose-100 text-rose-700 dark:bg-rose-500/15 dark:text-rose-300',
    'bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-300',
    'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300',
    'bg-sky-100 text-sky-700 dark:bg-sky-500/15 dark:text-sky-300',
    'bg-violet-100 text-violet-700 dark:bg-violet-500/15 dark:text-violet-300',
];

function colorFor(name: string) {
    let hash = 0;

    for (let i = 0; i < name.length; i++) {
        hash = name.charCodeAt(i) + ((hash << 5) - hash);
    }

    return PALETTE[Math.abs(hash) % PALETTE.length];
}

function initialsFor(name: string) {
    const parts = name.trim().split(/\s+/).filter(Boolean);

    if (parts.length === 0) {
        return '?';
    }

    const first = parts[0][0];
    const last = parts.length > 1 ? parts[parts.length - 1][0] : '';

    return (first + last).toUpperCase();
}

type AppAvatarProps = {
    name: string;
    className?: string;
};

/** Círculo de iniciais com cor determinística a partir do nome, para listas de pessoas/utentes. */
export function AppAvatar({ name, className }: AppAvatarProps) {
    return (
        <span className={cn('inline-flex size-9 shrink-0 items-center justify-center rounded-full text-xs font-semibold', colorFor(name), className)}>
            {initialsFor(name)}
        </span>
    );
}
