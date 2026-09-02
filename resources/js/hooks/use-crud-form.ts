import type { Errors, FormDataConvertible, Page } from '@inertiajs/core';
import { router } from '@inertiajs/react';
import { FormEvent, useState } from 'react';
import toast from 'react-hot-toast';

type CrudFormData = Record<string, FormDataConvertible>;

interface UseCrudFormOptions<T extends CrudFormData> {
    /** URL usado no pedido de criação (router.post) ou atualização (router.put). */
    url: string;
    /** Quando true, usa router.put em vez de router.post. */
    isEditing: boolean;
    successMessage: string;
    errorMessage?: string;
    preserveScroll?: boolean;
    onSuccess?: (page: Page) => void;
}

/**
 * Centraliza o padrão repetido de formulários Create/Update:
 * estado do form, loading, erros de validação e submissão via Inertia router.
 */
export function useCrudForm<T extends CrudFormData>(initialData: T, options: UseCrudFormOptions<T>) {
    const { url, isEditing, successMessage, errorMessage = 'Verifique os dados introduzidos.', preserveScroll = true, onSuccess } = options;

    const [form, setForm] = useState<T>(initialData);
    const [errors, setErrors] = useState<Errors>({});
    const [loading, setLoading] = useState(false);

    const updateField = <K extends keyof T>(field: K, value: T[K]) => {
        setForm((current) => ({
            ...current,
            [field]: value,
        }));

        setErrors((current) => ({
            ...current,
            [field as string]: '',
        }));
    };

    const resetForm = (data: T) => {
        setForm(data);
        setErrors({});
    };

    const submit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        setLoading(true);
        setErrors({});

        const requestOptions = {
            preserveScroll,

            onError: (formErrors: Errors) => {
                setErrors(formErrors);
                toast.error(errorMessage);
            },

            onFinish: () => {
                setLoading(false);
            },

            onSuccess: (page: Page) => {
                toast.success(successMessage);
                onSuccess?.(page);
            },
        };

        if (isEditing) {
            router.put(url, form, requestOptions);
        } else {
            router.post(url, form, requestOptions);
        }
    };

    return { form, setForm, errors, setErrors, loading, updateField, resetForm, submit };
}
