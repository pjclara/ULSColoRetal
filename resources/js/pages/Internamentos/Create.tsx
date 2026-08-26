import { AppPageHeader } from '@/components/app/app-page-header';
import { AppWizard } from '@/components/app/app-wizard';
import AppLayout from '@/layouts/app-layout';
import type { UtenteItem } from '@/types/type';
import { Head, router } from '@inertiajs/react';
import { useState } from 'react';

import { StepUtente } from '../Utentes/StepUtente';
// import { StepInternamento } from './StepInternamento';

type Props = {
    utentes: UtenteItem[];

    filters: {
        search: string;
    };

    // Adicionar aqui os dados necessários
    // para o segundo passo quando o backend os enviar.
};

const breadcrumbs = [
    {
        title: 'Internamentos',
        href: route('internamentos.index'),
    },
    {
        title: 'Criar Internamento',
        href: route('internamentos.create'),
    },
];

const steps = [
    {
        id: 'utente',
        title: 'Utente',
        description: 'Procurar ou criar',
    },
    {
        id: 'internamento',
        title: 'Internamento',
        description: 'Preencher os dados do internamento',
    },
];

export default function Create({ utentes, filters }: Props) {
    const [currentStep, setCurrentStep] = useState(0);

    const [utente, setUtente] = useState<UtenteItem | null>(null);

    /**
     * Utente selecionado no primeiro passo.
     */
    const handleSelectUtente = (selected: UtenteItem) => {
        setUtente(selected);
        setCurrentStep(1);

        router.get(
            route('internamentos.create'),
            {
                utente_id: selected.id,
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            },
        );
    };

    /**
     * Avançar manualmente para o passo seguinte.
     */
    const handleContinue = () => {
        if (!utente) {
            return;
        }

        setCurrentStep(1);

        router.get(
            route('internamentos.create'),
            {
                utente_id: utente.id,
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            },
        );
    };

    /**
     * Voltar ao passo do utente.
     */
    const handleBack = () => {
        setCurrentStep(0);
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Criar Internamento" />

            <div className="p-6">
                <AppPageHeader title="Criar Internamento" description="Preencha os campos abaixo para criar um novo internamento." />

                <div className="mx-auto max-w-6xl space-y-8">
                    <AppWizard steps={steps} currentStep={currentStep} />

                    {/* PASSO 1 — UTENTE */}
                    {currentStep === 0 && (
                        <StepUtente
                            utentes={utentes}
                            filters={filters}
                            selectedUtente={utente}
                            onCreate={(newUtente:any) => {
                                setUtente(newUtente);
                                handleContinue();
                            }}
                            onSelect={handleSelectUtente}
                            onContinue={handleContinue}
                            url={route('internamentos.create')}
                        />
                    )}

                    {/* PASSO 2 — INTERNAMENTO */}
                    {currentStep === 1 && utente && (
                        <div className="space-y-6">
                            <div className="rounded-lg border p-6">
                                <h2 className="text-lg font-semibold">Internamento</h2>

                                <p className="text-muted-foreground mt-1 text-sm">
                                    Utente selecionado: <strong>{utente.nome} - {utente.numero_processo}</strong>
                                </p>
                            </div>

                            {/*
                            Aqui entra o componente:

                            <StepInternamento
                                utente={utente}
                                onBack={handleBack}
                                onSuccess={...}
                            />
                            */}
                        </div>
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
