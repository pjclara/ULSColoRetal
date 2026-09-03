import { AppFormField } from './app-form-field';

export type Option = {
    value: string;
    label: string;
    disabled?: boolean;
};

type Props = {
    id?: string;
    value: string;
    onChange: (value: string) => void;
    options: Option[];
    placeholder?: string;
    disabled?: boolean;
    required?: boolean;
    className?: string;
    error?: string;
    label: string;
};

export function AppSelectField({
    id,
    value,
    onChange,
    options,
    error,
    placeholder = 'Selecione...',
    disabled = false,
    required = false,
    className = '',
    label,
}: Props) {
    return (
        <AppFormField label={label} error={error}>
            <select
                id={id}
                value={value ?? ''}
                onChange={(event) => onChange(event.target.value)}
                disabled={disabled}
                required={required}
                className={`w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm dark:border-neutral-700 dark:bg-neutral-900 ${className}`}
            >
                <option value="">
                    {placeholder}
                </option>

                {options.map((option) => (
                    <option
                        key={option.value}
                        value={option.value}
                        disabled={option.disabled}
                    >
                        {option.label}
                    </option>
                ))}
            </select>
        </AppFormField>
    );
}
