import { router } from '@inertiajs/react';
import { ReactNode } from 'react';

import { AppEntitySummary } from '@/components/app/app-entity-summary';
import { Button } from '@/components/ui/button';

type SummarySection = {
    title: string;
    fields: { label: string; value: ReactNode }[];
};

type Props = {
    doente: Doente;
    episodio: Episodio;
    successMessage: string;
    sections?: SummarySection[];
    backLabel: string;
    backUrl: string;
    viewLabel?: string;
    viewUrl?: string;
};

/** Passo final de confirmação partilhado por todos os wizards (Tratamento, CDT, Avaliação ERAS, Caso Planeado, ...). */
export function StepConfirmation({ doente, episodio, successMessage, sections = [], backLabel, backUrl, viewLabel, viewUrl }: Props) {
    return (
        <div className="space-y-6">
            <div className="rounded-xl border border-green-200 bg-green-50 p-6 text-center dark:border-green-900 dark:bg-green-950">
                <p className="text-lg font-semibold text-green-700 dark:text-green-400">{successMessage}</p>
            </div>

            <AppEntitySummary
                title="Doente"
                fields={[
                    { label: 'Nome', value: doente.nome },
                    { label: 'PU', value: doente.pu },
                ]}
            />

            <AppEntitySummary
                title="Episódio"
                fields={[
                    { label: 'Tipo', value: episodio.tipo },
                    { label: 'Diagnóstico', value: episodio.diagnostico },
                    { label: 'Estado', value: episodio.estado },
                ]}
            />

            {sections.map((section) => (
                <AppEntitySummary key={section.title} title={section.title} fields={section.fields} />
            ))}

            <div className="flex justify-end gap-3 border-t border-neutral-200 pt-5 dark:border-neutral-800">
                <Button type="button"  onClick={() => router.get(backUrl)}>
                    {backLabel}
                </Button>

                {viewUrl && (
                    <Button type="button" onClick={() => router.get(viewUrl)}>
                        {viewLabel}
                    </Button>
                )}
            </div>
        </div>
    );
}
