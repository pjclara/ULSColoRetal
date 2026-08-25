import { ReactNode } from 'react';

export interface AppTableColumn<T> {
    key: string;
    label: string;
    render?: (item: T) => ReactNode;
    className?: string;
}

interface AppTableProps<T> {
    columns: AppTableColumn<T>[];
    data: T[];
    emptyMessage?: string;
    rowKey?: (item: T, index: number) => string | number;
}

export function AppTable<T>({
    columns,
    data,
    emptyMessage = 'Não existem registos.',
    rowKey,
}: AppTableProps<T>) {
    return (
        <div className="overflow-hidden rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
            <div className="overflow-x-auto">
                <table className="w-full text-sm">
                    <thead className="border-b border-neutral-200 bg-neutral-50 dark:border-neutral-800 dark:bg-neutral-950">
                        <tr>
                            {columns.map((column) => (
                                <th
                                    key={column.key}
                                    scope="col"
                                    className={`px-4 py-3 text-left font-medium text-neutral-600 dark:text-neutral-400 ${column.className ?? ''}`}
                                >
                                    {column.label}
                                </th>
                            ))}
                        </tr>
                    </thead>

                    <tbody className="divide-y divide-neutral-200 dark:divide-neutral-800">
                        {data.length === 0 ? (
                            <tr>
                                <td
                                    colSpan={columns.length}
                                    className="px-4 py-10 text-center text-neutral-500 dark:text-neutral-400"
                                >
                                    {emptyMessage}
                                </td>
                            </tr>
                        ) : (
                            data.map((item, index) => (
                                <tr
                                    key={
                                        rowKey
                                            ? rowKey(item, index)
                                            : index
                                    }
                                    className="transition-colors hover:bg-neutral-50 dark:hover:bg-neutral-800/50"
                                >
                                    {columns.map((column) => (
                                        <td
                                            key={column.key}
                                            className={`px-4 py-3 text-neutral-700 dark:text-neutral-300 ${column.className ?? ''}`}
                                        >
                                            {column.render
                                                ? column.render(item)
                                                : String(
                                                      item[
                                                          column.key as keyof T
                                                      ] ?? '',
                                                  )}
                                        </td>
                                    ))}
                                </tr>
                            ))
                        )}
                    </tbody>
                </table>
            </div>
        </div>
    );
}