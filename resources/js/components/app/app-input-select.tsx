import { AppFormField } from "./app-form-field";

type Option = {
    value: string | number;
    label: string;
    disabled?: boolean;
};

type Props = {
    id?: string;
    value: string | number;
    onChange: (value: string | number) => void;
    options: Option[];
    placeholder?: string;
    disabled?: boolean;
    required?: boolean;
    className?: string;
    error?: string;
    label: string;
};

export function AppSelectField({ id, value, onChange, options, error, placeholder = 'Selecione...', disabled, required, className = '', label }: Props) {
    return (
        <AppFormField label={label} error={error}>
            <select
                id={id}
                value={value}
                onChange={(e) => onChange(e.target.value as string | number)}
                disabled={disabled}
                required={required}
                className={`w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm dark:border-neutral-700 dark:bg-neutral-900 ${className}`}
            >
                <option value="">{placeholder}</option>

                {options.map((option) => (
                    <option key={option.value} value={option.value} disabled={option.disabled}>
                        {option.label}
                    </option>
                ))}
            </select>
        </AppFormField>
    );
}
