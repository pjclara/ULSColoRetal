import type { Errors, FormDataConvertible, Page } from '@inertiajs/core';
import { router } from '@inertiajs/react';
import { FormEvent, useCallback, useState } from 'react';
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
    /** Ajusta os dados imediatamente antes de serem enviados (ex: converter datas para ISO). */
    transform?: (data: T) => T;
}

/**
 * Centraliza o padrão repetido de formulários Create/Update:
 * estado do form, loading, erros de validação e submissão via Inertia router.
 */
export function useCrudForm<T extends CrudFormData>(initialData: T, options: UseCrudFormOptions<T>) {
    const { url, isEditing, successMessage, errorMessage = 'Verifique os dados introduzidos.', preserveScroll = true, onSuccess, transform } = options;

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

    const resetForm = useCallback((data: T) => {
        setForm(data);
        setErrors({});
    }, []);

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

        const data = transform ? transform(form) : form;

        if (isEditing) {
            router.put(url, data, requestOptions);
        } else {
            router.post(url, data, requestOptions);
        }
    };

    return { form, setForm, errors, setErrors, loading, updateField, resetForm, submit };
}
