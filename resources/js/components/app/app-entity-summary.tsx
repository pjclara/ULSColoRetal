import { ReactNode } from 'react';

interface AppEntitySummaryField {
    label: string;
    value: ReactNode;
}

interface AppEntitySummaryProps {
    title: string;
    fields: AppEntitySummaryField[];
    action?: ReactNode;
}

/** Cartão de resumo compacto para uma entidade selecionada (doente, episódio, etc.) num wizard. */
export function AppEntitySummary({ title, fields, action }: AppEntitySummaryProps) {
    return (
        <div className="rounded-xl border border-neutral-200 bg-neutral-50 p-5 dark:border-neutral-800 dark:bg-neutral-950">
            <div className="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p className="text-sm text-neutral-500 dark:text-neutral-400">{title}</p>

                    <dl className="mt-2 grid gap-x-6 gap-y-1 text-sm sm:grid-cols-2 lg:grid-cols-3">
                        {fields.map((field) => (
                            <div key={field.label}>
                                <dt className="inline font-medium text-neutral-700 dark:text-neutral-300">{field.label}: </dt>
                                <dd className="inline text-neutral-600 dark:text-neutral-400">{field.value ?? '—'}</dd>
                            </div>
                        ))}
                    </dl>
                </div>

                {action && <div className="shrink-0">{action}</div>}
            </div>
        </div>
    );
}
