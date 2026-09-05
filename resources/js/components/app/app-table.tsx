import React, { ReactNode } from 'react';

export interface AppTableColumn<T> {
    key: string;
    label: string;
    render?: (item: T) => ReactNode;
    className?: string;
    fullRow?: boolean;
}

interface AppTableProps<T> {
    columns: AppTableColumn<T>[];
    data: T[];
    emptyMessage?: string;
    rowKey?: (item: T, index: number) => string | number;
}

export function AppTable<T>({ columns, data, emptyMessage = 'Não existem registos.', rowKey }: AppTableProps<T>) {
    const normalColumns = columns.filter((column) => !column.fullRow);
    const fullRowColumns = columns.filter((column) => column.fullRow);

    return (
        <div className="overflow-hidden rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
            <div className="overflow-x-auto">
                <table className="w-full text-sm">
                    <thead className="border-b border-neutral-200 bg-neutral-50 dark:border-neutral-800 dark:bg-neutral-950">
                        <tr>
                            {normalColumns.map((column) => (
                                <th
                                    key={column.key}
                                    scope="col"
                                    className={`px-4 py-3 text-left text-xs font-semibold tracking-wide text-neutral-500 uppercase dark:text-neutral-400 ${column.className ?? ''} `}
                                >
                                    {column.label}
                                </th>
                            ))}
                        </tr>
                    </thead>

                    <tbody className="divide-y divide-neutral-200 dark:divide-neutral-800">
                        {data.length === 0 ? (
                            <tr>
                                <td colSpan={normalColumns.length} className="px-4 py-12 text-center text-sm text-neutral-500">
                                    {emptyMessage}
                                </td>
                            </tr>
                        ) : (
                            data.map((item, index) => (
                                <React.Fragment key={rowKey ? rowKey(item, index) : index}>
                                    {/* Linha principal */}
                                    <tr className="group transition-colors hover:bg-neutral-50 dark:hover:bg-neutral-800/40">
                                        {normalColumns.map((column) => (
                                            <td
                                                key={column.key}
                                                className={`px-4 py-3.5 align-middle text-neutral-700 dark:text-neutral-300 ${
                                                    column.className ?? ''
                                                }`}
                                            >
                                                {column.render ? column.render(item) : String(item[column.key as keyof T] ?? '')}
                                            </td>
                                        ))}
                                    </tr>

                                    {/* Linha de detalhe */}
                                    {fullRowColumns.map((column) => (
                                        <tr key={`${column.key}-${index}`} className="bg-neutral-50/70 dark:bg-neutral-900/50">
                                            <td colSpan={normalColumns.length} className={`px-4 pt-1 pb-4 text-sm ${column.className ?? ''}`}>
                                                <div className="border-l-2 border-neutral-300 pl-4 text-neutral-500 dark:border-neutral-700 dark:text-neutral-400">
                                                    {column.render ? column.render(item) : String(item[column.key as keyof T] ?? '')}
                                                </div>
                                            </td>
                                        </tr>
                                    ))}
                                </React.Fragment>
                            ))
                        )}
                    </tbody>
                </table>
            </div>
        </div>
    );
}
