import { ReactNode, useEffect, useId } from 'react';
import { Button } from '@/components/ui/button';
import { X } from 'lucide-react';

interface AppModalProps {
    open: boolean;
    title: string;
    description?: string;
    children: ReactNode;
    footer?: ReactNode;
    onClose: () => void;

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

    closeOnOverlayClick?: boolean;
    closeOnEscape?: boolean;
}

export function AppModal({
    open,
    title,
    description,
    children,
    footer,
    onClose,
    maxWidth = '4xl',
    closeOnOverlayClick = true,
    closeOnEscape = true,
}: AppModalProps) {
    const titleId = useId();
    const descriptionId = useId();

    useEffect(() => {
        if (!open || !closeOnEscape) {
            return;
        }

        const handleKeyDown = (event: KeyboardEvent) => {
            if (event.key === 'Escape') {
                onClose();
            }
        };

        document.addEventListener('keydown', handleKeyDown);

        return () => {
            document.removeEventListener('keydown', handleKeyDown);
        };
    }, [open, closeOnEscape, onClose]);

    useEffect(() => {
        if (!open) {
            return;
        }

        const originalOverflow = document.body.style.overflow;

        document.body.style.overflow = 'hidden';

        return () => {
            document.body.style.overflow = originalOverflow;
        };
    }, [open]);

    if (!open) {
        return null;
    }

    const widths = {
        sm: 'max-w-sm',
        md: 'max-w-md',
        lg: 'max-w-lg',
        xl: 'max-w-xl',
        '2xl': 'max-w-2xl',
        '3xl': 'max-w-3xl',
        '4xl': 'max-w-4xl',
        '5xl': 'max-w-5xl',
        '6xl': 'max-w-6xl',
        '7xl': 'max-w-7xl',
        full: 'max-w-[calc(100vw-2rem)]',
    };

    return (
        <div
            className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm"
            role="dialog"
            aria-modal="true"
            aria-labelledby={titleId}
            aria-describedby={description ? descriptionId : undefined}
            onMouseDown={(event) => {
                if (
                    closeOnOverlayClick &&
                    event.target === event.currentTarget
                ) {
                    onClose();
                }
            }}
        >
            <div
                className={`
                    flex
                    max-h-[calc(100vh-2rem)]
                    w-full
                    ${widths[maxWidth]}
                    flex-col
                    overflow-hidden
                    rounded-2xl
                    border
                    border-neutral-200
                    bg-white
                    shadow-2xl
                    dark:border-neutral-800
                    dark:bg-neutral-900
                `}
            >
                {/* Header */}
                <div className="flex shrink-0 items-start justify-between gap-4 border-b border-neutral-200 px-6 py-5 dark:border-neutral-800">
                    <div className="min-w-0">
                        <h2
                            id={titleId}
                            className="text-xl font-semibold tracking-tight"
                        >
                            {title}
                        </h2>

                        {description && (
                            <p
                                id={descriptionId}
                                className="mt-1 text-sm text-neutral-500 dark:text-neutral-400"
                            >
                                {description}
                            </p>
                        )}
                    </div>

                    <Button
                        type="button"
                        variant="ghost"
                        size="icon"
                        onClick={onClose}
                        aria-label="Fechar"
                        className="shrink-0"
                    >
                        <X className="size-4" />
                    </Button>
                </div>

                {/* Content */}
                <div className="min-h-0 flex-1 overflow-y-auto px-6 py-6">
                    {children}
                </div>

                {/* Footer */}
                {footer && (
                    <div className="flex shrink-0 items-center justify-end gap-3 border-t border-neutral-200 bg-neutral-50 px-6 py-4 dark:border-neutral-800 dark:bg-neutral-950/50">
                        {footer}
                    </div>
                )}
            </div>
        </div>
    );
}