import { AppFormField } from './app-form-field';

interface AppTextareaFieldProps {
    label: string;
    value: string;
    onChange: (value: string) => void;
    error?: string;
    disabled?: boolean;
    required?: boolean;
    className?: string;
}

export function AppTextareaField({
    label,
    value,
    onChange,
    error,
    disabled = false,
    required = false,
    className,
}: AppTextareaFieldProps) {
    return (
        <AppFormField label={label} error={error}>
            <label className="flex cursor-pointer items-center gap-3">
                <textarea
                    value={value}
                    onChange={(event) => onChange(event.target.value)}
                    disabled={disabled}
                    required={required}
                    className={`block w-full rounded-md border border-input bg-background px-3 py-2 text-sm placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 ${className ?? ''}`}
                />
            </label>
        </AppFormField>
    );
}