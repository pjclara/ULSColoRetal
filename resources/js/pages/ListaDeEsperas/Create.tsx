import { AppPageHeader } from '@/components/app/app-page-header';
import { AppWizard } from '@/components/app/app-wizard';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { DestinoItem, ListaDeEsperaItem, Option, User, UtenteItem, origensDaReferenciacaoItem } from '@/types/type';
import { Head, router } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import StepCentroDeReferencia from '../CentroDeReferencias/StepCentroDeReferencia';
import { StepConfirmation } from '../StepConfirmation';
import { StepUtente } from '../Utentes/StepUtente';
import StepListaDeEspera from './StepListaDeEspera';
import StepAgendamentos from '../Agendamentos/StepAgendamentos';

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
    estadoOptions: Option[];
    diagnosticosOptions: Option[];
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
        id: 'agendamento',
        title: 'Agendamento',
        description: 'Preencher os dados do agendamento',
    },
    {
        id: 'confirmacao',
        title: 'Confirmação',
        description: 'Confirmar os dados antes de submeter',
    },
];

export default function Index({ utentes, filters, utente, origems, destinos, users, estadoOptions, diagnosticosOptions }: Props) {
    const [currentStep, setCurrentStep] = useState(0);
    const [utenteState, setUtente] = useState<UtenteItem | null>(utente ?? null);
    const [selectedListaDeEspera, setSelectedListaDeEspera] = useState<ListaDeEsperaItem | null>(utente?.lista_de_esperas?.[0] ?? null);

    useEffect(() => {
        setUtente(utente ?? null);
        setSelectedListaDeEspera(utente?.lista_de_esperas?.[0] ?? null);
    }, [utente]);

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
                        <Button type="button" size="sm" onClick={() => router.get(route('lista-de-esperas.index'))}>
                            Voltar a lista de espera
                        </Button>
                    }
                />

                <div className="mx-auto max-w-6xl">
                    <AppWizard steps={steps} currentStep={currentStep}>
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

                        {currentStep === 2 && utenteState && (
                            <StepListaDeEspera
                                diagnosticosOptions={diagnosticosOptions}
                                utente={utenteState}
                                centroDeReferencia={utenteState.centro_de_referencia}
                                onContinue={(lista) => {
                                    setSelectedListaDeEspera(lista);
                                    setCurrentStep(3);
                                }}
                                onBack={() => setCurrentStep(1)}
                                estadoOptions={estadoOptions}
                                responsavelOptions={users.map((user) => ({
                                    value: user.id,
                                    label: user.name,
                                }))}
                            />
                        )}

                        {currentStep === 3 && utenteState && (
                            selectedListaDeEspera ? (
                                <StepAgendamentos
                                    utente={utenteState}
                                    centroDeReferencia={utenteState.centro_de_referencia}
                                    listaDeEspera={selectedListaDeEspera}
                                    onContinue={() => setCurrentStep(4)}
                                    onBack={() => setCurrentStep(2)}
                                />
                            ) : null
                        )}

                        {currentStep === 4 && utenteState && (
                            <StepConfirmation
                                successMessage="Lista de Espera criada com sucesso"
                                backLabel="Novo registo"
                                backUrl={route('lista-de-esperas.create')}
                                viewLabel="Ver"
                                viewUrl={route('lista-de-esperas.index')}
                                sections={[
                                    {
                                        title: 'Utente',
                                        fields: [
                                            { label: 'Nome', value: utenteState?.nome ?? '' },
                                            { label: 'Numero_processo', value: utenteState?.numero_processo ?? '' },
                                        ],
                                    },
                                    {
                                        title: 'Centro de Referência',
                                        fields: [{ label: 'Nome', value: utenteState?.centro_de_referencia?.id ?? '' }],
                                    },
                                ]}
                            />
                        )}
                    </AppWizard>
                </div>
            </div>
        </AppLayout>
    );
}
