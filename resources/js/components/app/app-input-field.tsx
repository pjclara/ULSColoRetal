import { Input } from '@/components/ui/input';
import { ReactNode } from 'react';
import { AppFormField } from './app-form-field';

interface AppInputFieldProps {
    label: string;
    value: string | number;
    onChange: (value: string) => void;
    error?: string;
    placeholder?: string;
    type?: 'text' | 'email' | 'password' | 'date'   | 'number' | 'time';
    disabled?: boolean;
    required?: boolean;
    className?: string;
}

export function AppInputField({
    label,
    value,
    onChange,
    error,
    placeholder,
    type = 'text',
    disabled = false,
    required = false,
    className,
}: AppInputFieldProps) {
    return (
        <AppFormField
            label={label}
            error={error}
        >
            <Input
                type={type}
                value={value}
                onChange={(event) => onChange(event.target.value)}
                placeholder={placeholder}
                disabled={disabled}
                required={required}
                className={className}
            />
        </AppFormField>
    );
}