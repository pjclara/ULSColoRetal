import { ReactNode } from 'react';

interface AppPageHeaderProps {
    title: string;
    description?: string;
    action?: ReactNode;
    children?: ReactNode;
}

export function AppPageHeader({
    title,
    description,
    action,
    children,
}: AppPageHeaderProps) {
    return (
        <div className="mb-6">
            <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 className="text-2xl font-semibold tracking-tight text-neutral-900 dark:text-white">
                        {title}
                    </h1>

                    {description && (
                        <p className="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                            {description}
                        </p>
                    )}
                </div>

                {action && (
                    <div className="flex items-center gap-2">
                        {action}
                    </div>
                )}
            </div>

            {children && (
                <div className="mt-4">
                    {children}
                </div>
            )}
        </div>
    );
}