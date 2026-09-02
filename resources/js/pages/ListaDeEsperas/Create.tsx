import { AppPageHeader } from '@/components/app/app-page-header';
import { AppWizard } from '@/components/app/app-wizard';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { UtenteItem, CentroDeReferenciaItem, origensDaReferenciacaoItem, DestinoItem, User } from '@/types/type';
import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import { StepUtente } from '../Utentes/StepUtente';
import StepCentroDeReferencia from '../CentroDeReferencias/StepCentroDeReferencia';

type Props = {
    utentes: UtenteItem[];
    filters: {
        search?: string;
        page?: number;
    };
    utente?: UtenteItem | null;
    origems: origensDaReferenciacaoItem[];
    destinos: DestinoItem[];
    users: User[];
};

const breadcrumbs = [
    {
        title: 'Lista de Espera',
        href: route('lista-de-esperas.index'),
    },
    {
        title: 'Adicionar',
        href: route('lista-de-esperas.create'),
    },
];

const steps = [
    {
        id: 'utente',
        title: 'Utente',
        description: 'Procurar ou criar',
    },
    {
        id: 'centro_de_referencia',
        title: 'Centro de Referência',
        description: 'Preencher os dados do centro de referência',
    },
    {
        id: 'lista_de_espera',
        title: 'Lista de Espera',
        description: 'Preencher os dados da lista de espera',
    },
    {
        id: 'confirmacao',
        title: 'Confirmação',
        description: 'Rever e confirmar os dados introduzidos',
    },
];

export default function Index({ utentes, filters, utente, origems, destinos, users }: Props) {
    const [currentStep, setCurrentStep] = useState(0);
    const [utenteState, setUtente] = useState<UtenteItem | undefined>(utente ?? undefined);

    const goToNextStep = (utenteId: number | null) => {
        if (!utenteId) return;
        setCurrentStep(1);

        router.get(
            route('lista-de-esperas.create'),
            {
                utente_id: utenteId,
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            },
        );
    };

    const handleContinue = () => {
        if (!utenteState) {
            return;
        }

        goToNextStep(utenteState.id);
    };

    const handleSelectUtente = (selected: UtenteItem) => {
        setUtente(selected);
        goToNextStep(selected.id);
    };

    const handleBack = () => {
        setCurrentStep(currentStep - 1);
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Lista de Espera" />

            <div className="p-6">
                <AppPageHeader
                    title="Lista de Espera"
                    description="Adicionar um novo registo à lista de espera."
                    action={
                        <Button type="button" size="sm" onClick={() => router.get(route('lista-de-esperas.create'))}>
                            Novo registo
                        </Button>
                    }
                />

                <div className="mx-auto max-w-6xl space-y-8">
                    <AppWizard steps={steps} currentStep={currentStep} />
                </div>

                {currentStep === 0 && (
                    <StepUtente
                        utentes={utentes}
                        filters={filters}
                        selectedUtente={utenteState}
                        onCreate={(newUtente) => {
                            setUtente(newUtente);
                            goToNextStep(newUtente.id);
                        }}
                        onSelect={handleSelectUtente}
                        onContinue={handleContinue}
                        url={route('lista-de-esperas.create')}
                    />
                )}

                {/* PASSO 2 — CENTRO DE REFERENCIA */}
                {currentStep === 1 && utenteState && (
                    <StepCentroDeReferencia
                        users={users}
                        origems={origems}
                        destinos={destinos}
                        utente={utenteState}
                        centroDeReferencia={utenteState.centro_de_referencia}
                        onBack={handleBack}
                        onContinue={() => setCurrentStep(2)}
                    />
                )}
            </div>
        </AppLayout>
    );
}
