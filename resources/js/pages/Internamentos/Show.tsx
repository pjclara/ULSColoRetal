import { AppEntitySummary } from '@/components/app/app-entity-summary';
import { AppPageHeader } from '@/components/app/app-page-header';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import CentroDeReferenciaModal from '../CentroDeReferencias/CentroDeReferenciaModal';
import AddDiagnosticosToInternamento from '../Diagnosticos/AddDiagnosticosToInternamento';
import CreateOrUpdateUtente from '../Utentes/CreateOrUpdateUtente';
import CreateOrUpdateInternamentoModal from './CreateOrUpdateInternamentoModal';
import { InternamentoOptions } from '@/types/internamento';

type Props = {
    internamento: any;
    utente: any;
    centroDeReferencia: any;
    diagnosticosAgrupados: Record<string, any[]>;
    options: InternamentoOptions
};

const breadcrumbs = [
    { title: 'Internamentos', href: route('internamentos.index') },
    { title: 'Detalhes do Internamento', href: '' },
];

export default function ShowInternamento({ internamento, utente, centroDeReferencia, options, diagnosticosAgrupados }: Props) {
    const [showCentroModal, setShowCentroModal] = useState(false);
    const [isOpenUtente, setIsOpenUtente] = useState(false);
    const [isInternamentoOpen, setIsInternamentoOpen] = useState(false);

    const openCentroModal = () => setShowCentroModal(true);
    const closeCentroModal = () => setShowCentroModal(false);
    const [showDiagnosticosToInternamentoModal, setShowDiagnosticosToInternamentoModal] = useState(false);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Internamentos" />

            <div className="p-6">
                <AppPageHeader title="Internamentos" description="Gestão do internamento do utente." />

                <div className="mt-6 grid gap-6 space-y-8 sm:grid-cols-1 lg:grid-cols-2">
                    {/* UTENTE */}
                    <AppEntitySummary
                        title="Utente"
                        fields={[
                            { label: 'Nome', value: internamento.utente.nome },
                            { label: 'N.º Processo', value: internamento.utente.numero_processo },
                            {
                                label: 'Idade',
                                value: internamento.utente.idade ? `${internamento.utente.idade} anos` : '—',
                            },
                        ]}
                        action={
                            <div className="flex flex-col gap-2 p-3">
                                <Button variant="outline" onClick={() => setIsOpenUtente(true)}>
                                    Editar Utente
                                </Button>
                            </div>
                        }
                    />

                    {/* INTERNAMENTO */}
                    <AppEntitySummary
                        title="Dados do Internamento"
                        fields={[
                            { label: 'Entrada', value: internamento.data_de_entrada },
                            { label: 'Saída', value: internamento.data_de_saida || '—' },
                            { label: 'Dias de internamento', value: internamento.dias_internamento },
                            { label: 'Origem', value: internamento.origem_do_internamento?.nome },
                            { label: 'Destino', value: internamento.destino?.nome },
                            { label: 'Responsável', value: internamento.responsavel?.name },
                            { label: 'Clavien-Dindo', value: internamento.clavien_dindo?.nome },
                            { label: 'Observações', value: internamento.observacoes || '—' },
                        ]}
                        action={
                            <div className="flex flex-col gap-2 p-3">
                                <Button variant="outline" onClick={() => setIsInternamentoOpen(true)}>
                                    Editar Internamento
                                </Button>
                            </div>
                        }
                    />

                    {/* DIAGNÓSTICOS */}
                    <AppEntitySummary
                        title="Diagnósticos"
                        fields={[{ value: internamento.diagnosticos.map((diagnostico: any) => diagnostico.nome).join(', ') || '—' }]}
                        action={
                            <div className="flex flex-col gap-2 p-3">
                                <Button variant="outline" onClick={() => setShowDiagnosticosToInternamentoModal(true)}>
                                    Ver Diagnósticos
                                </Button>
                            </div>
                        }
                    />

                    {/* BLOCO OPERATÓRIO */}
                    <AppEntitySummary
                        title="Bloco Operatório"
                        fields={[
                            { label: 'N.º de Cirurgias', value: internamento.bloco_operatorios_count },
                            { label: 'Última Cirurgia', value: internamento.ultima_cirurgia },
                        ]}
                        action={
                            <div className="flex flex-col gap-2 p-3">
                                <Button variant="outline" onClick={() => router.get(`/internamentos/${internamento.id}/cirurgias`)}>
                                    Ver Cirurgias
                                </Button>
                            </div>
                        }
                    />

                    {/* CENTRO DE REFERÊNCIA */}

                    <AppEntitySummary
                        title="Centro de Referência"
                        fields={[
                            { label: 'Data de Diagnóstico', value: centroDeReferencia?.data_de_diagnostico },
                            { label: 'Data de Referenciação', value: centroDeReferencia?.data_de_referenciacao },
                            { label: 'Origem', value: centroDeReferencia?.origem?.nome },
                            { label: 'Destino', value: centroDeReferencia?.destino?.nome },
                            { label: 'Responsável', value: centroDeReferencia?.responsavel?.name },
                            { label: 'Comentários', value: centroDeReferencia?.comentarios },
                        ]}
                        action={
                            <div className="flex flex-col gap-2 p-3">
                                <Button variant="outline" onClick={openCentroModal}>
                                    Editar Centro de Referência
                                </Button>
                            </div>
                        }
                    />

                    {/* COMPLICAÇÕES */}
                    <AppEntitySummary
                        title="Complicações"
                        fields={[{ label: 'N.º de Complicações', value: internamento.complicacaos_count }]}
                        action={
                            <div className="flex flex-col gap-2 p-3">
                                <Button variant="outline" onClick={() => router.get(`/internamentos/${internamento.id}/complicacoes`)}>
                                    Ver Complicações
                                </Button>
                            </div>
                        }
                    />

                    {/* MODAL */}
                    <CentroDeReferenciaModal
                        open={showCentroModal}
                        onClose={closeCentroModal}
                        utente={utente}
                        centroDeReferencia={centroDeReferencia}
                        origens={options.origens}
                        destinos={options.destinos}
                        responsaveis={options.responsaveis}
                        onSuccess={() => {
                            closeCentroModal();
                            router.reload({ only: ['centroDeReferencia'] });
                        }}
                    />

                    {/* Modal internamento */}

                    <CreateOrUpdateInternamentoModal
                        open={isInternamentoOpen}
                        internamento={internamento}
                        onClose={() => setIsInternamentoOpen(false)}
                        internamentoOptions={options}
                        onSubmit={() => {
                            router.reload({ only: ['internamento'] });
                            setIsInternamentoOpen(false);
                        }}
                    />

                    {/* Modal diagnotico */}
                    {showDiagnosticosToInternamentoModal && (
                        <AddDiagnosticosToInternamento
                            internamento={internamento}
                            diagnosticosAgrupados={diagnosticosAgrupados}
                            onSave={() => router.reload({ only: ['internamento'] })}
                        />
                    )}
                    {/* Modal utente */}
                    <CreateOrUpdateUtente
                        utente={internamento.utente}
                        onSubmit={() => {
                            router.reload({ only: ['utente'] });
                            setIsOpenUtente(false);
                        }}
                        onClose={() => setIsOpenUtente(false)}
                        onCancel={() => setIsOpenUtente(false)}
                        isOpenUtente={isOpenUtente}
                    />
                </div>
            </div>
        </AppLayout>
    );
}
