import { ReactNode } from 'react';
import { Button } from '@/components/ui/button';
import { AppModal } from './app-modal';

interface AppConfirmDialogProps {
    open: boolean;
    title: string;
    description?: string;

    onConfirm: () => void;
    onClose: () => void;

    loading?: boolean;

    confirmLabel?: string;
    cancelLabel?: string;
    loadingLabel?: string;

    variant?: 'default' | 'destructive';

    children?: ReactNode;
}

export function AppConfirmDialog({
    open,
    title,
    description,
    onConfirm,
    onClose,
    loading = false,
    confirmLabel = 'Confirmar',
    cancelLabel = 'Cancelar',
    loadingLabel = 'A processar...',
    variant = 'destructive',
    children,
}: AppConfirmDialogProps) {
    return (
        <AppModal
            open={open}
            title={title}
            description={description}
            onClose={onClose}
            maxWidth="md"
        >
            <div className="space-y-6">
                {children && (
                    <div className="text-sm text-neutral-700 dark:text-neutral-300">
                        {children}
                    </div>
                )}

                <div className="flex justify-end gap-3 border-t border-neutral-200 pt-5 dark:border-neutral-800">
                    <Button
                        type="button"
                        variant="outline"
                        onClick={onClose}
                        disabled={loading}
                    >
                        {cancelLabel}
                    </Button>

                    <Button
                        type="button"
                        variant={variant}
                        onClick={onConfirm}
                        disabled={loading}
                    >
                        {loading ? loadingLabel : confirmLabel}
                    </Button>
                </div>
            </div>
        </AppModal>
    );
}