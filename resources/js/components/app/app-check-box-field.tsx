import { Input } from '@/components/ui/input';
import { AppFormField } from './app-form-field';

interface AppCheckboxFieldProps {
    label: string;
    checked: boolean;
    onChange: (checked: boolean) => void;
    error?: string;
    disabled?: boolean;
    required?: boolean;
    className?: string;
}

export function AppCheckboxField({
    label,
    checked,
    onChange,
    error,
    disabled = false,
    required = false,
    className,
}: AppCheckboxFieldProps) {
    return (
        <AppFormField label={label} error={error}>
            <label className="flex cursor-pointer items-center gap-3">
                <Input
                    type="checkbox"
                    checked={checked}
                    onChange={(event) =>
                        onChange(event.target.checked)
                    }
                    disabled={disabled}
                    required={required}
                    className={`h-4 w-4 ${className ?? ''}`}
                />

                <span className="text-sm">
                    {label}
                </span>
            </label>
        </AppFormField>
    );
}