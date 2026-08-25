import { ReactNode } from 'react';

export interface AppWizardStep {
    id: string;
    title: string;
    description?: string;
}

interface AppWizardProps {
    steps: AppWizardStep[];
    currentStep: number;
    children?: ReactNode;
}

export function AppWizard({ steps, currentStep, children }: AppWizardProps) {
    return (
        <div className="space-y-8">
            {/* Progress */}
            <div className="flex items-start">
                {steps.map((step, index) => {
                    const active = index === currentStep;
                    const completed = index < currentStep;

                    return (
                        <div key={step.id} className="flex flex-1 items-start">
                            <div className="flex flex-col items-center">
                                <div
                                    className={`flex size-9 items-center justify-center rounded-full border text-sm font-semibold ${
                                        active
                                            ? 'border-primary bg-primary text-primary-foreground'
                                            : completed
                                              ? 'border-primary bg-primary/10 text-primary'
                                              : 'border-neutral-300 text-neutral-500 dark:border-neutral-700'
                                    } `}
                                >
                                    {completed ? '✓' : index + 1}
                                </div>

                                <div className="mt-2 text-center">
                                    <div className="text-sm font-medium">{step.title}</div>

                                    {step.description && <div className="hidden text-xs text-neutral-500 md:block">{step.description}</div>}
                                </div>
                            </div>

                            {index < steps.length - 1 && (
                                <div className={`mt-4 h-px flex-1 ${index < currentStep ? 'bg-primary' : 'bg-neutral-200 dark:bg-neutral-800'} `} />
                            )}
                        </div>
                    );
                })}
            </div>

            {/* Content */}
            {children && <div>{children}</div>}
        </div>
    );
}
