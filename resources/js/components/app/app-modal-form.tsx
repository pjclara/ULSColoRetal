import { FormEvent, ReactNode } from 'react';
import { AppModal } from './app-modal';
import { Button } from '@/components/ui/button';

interface AppModalFormProps {
    open: boolean;
    title: string;
    description?: string;

    children: ReactNode;

    onClose: () => void;
    onSubmit: (event: FormEvent<HTMLFormElement>) => void;

    loading?: boolean;

    submitLabel?: string;
    loadingLabel?: string;
    cancelLabel?: string;

    maxWidth?:
        | 'sm'
        | 'md'
        | 'lg'
        | 'xl'
        | '2xl'
        | '3xl'
        | '4xl'
        | '5xl'
        | '6xl'
        | '7xl'
        | 'full';

    submitDisabled?: boolean;
}

export function AppModalForm({
    open,
    title,
    description,
    children,
    onClose,
    onSubmit,
    loading = false,
    submitLabel = 'Guardar',
    loadingLabel = 'A guardar...',
    cancelLabel = 'Cancelar',
    maxWidth = '4xl',
    submitDisabled = false,
}: AppModalFormProps) {
    return (
        <AppModal
            open={open}
            title={title}
            description={description}
            onClose={onClose}
            maxWidth={maxWidth}
        >
            <form onSubmit={onSubmit}>
                <div className="space-y-6">
                    {children}
                </div>

                <div className="mt-8 flex items-center justify-end gap-3 border-t border-neutral-200 pt-5 dark:border-neutral-800">
                    <Button
                        type="button"
                        variant="outline"
                        onClick={onClose}
                        disabled={loading}
                    >
                        {cancelLabel}
                    </Button>

                    <Button
                        type="submit"
                        disabled={loading || submitDisabled}
                    >
                        {loading ? loadingLabel : submitLabel}
                    </Button>
                </div>
            </form>
        </AppModal>
    );
}