import { ReactNode, useId } from 'react';

interface AppFormFieldProps {
    label: string;
    children: ReactNode;
    error?: string | null;
    hint?: string;
    required?: boolean;
    htmlFor?: string;
}

export function AppFormField({
    label,
    children,
    error,
    hint,
    required = false,
    htmlFor,
}: AppFormFieldProps) {
    const generatedId = useId();
    const inputId = htmlFor ?? generatedId;

    return (
        <div className="space-y-1">
            <label
                htmlFor={inputId}
                className="block text-sm font-medium text-neutral-700 dark:text-neutral-300"
            >
                {label}

                {required && (
                    <span
                        className="ml-1 text-red-600"
                        aria-hidden="true"
                    >
                        *
                    </span>
                )}
            </label>

            {children}

            {hint && !error && (
                <p className="text-xs text-neutral-500 dark:text-neutral-400">
                    {hint}
                </p>
            )}

            {error && (
                <p
                    className="text-sm text-red-600 dark:text-red-400"
                    role="alert"
                >
                    {error}
                </p>
            )}
        </div>
    );
}