import { AppEntitySummary } from '@/components/app/app-entity-summary';
import { AppPageHeader } from '@/components/app/app-page-header';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import CentroDeReferenciaModal from '../CentroDeReferencias/CentroDeReferenciaModal';
import AddDiagnosticosToInternamento from '../Diagnosticos/AddDiagnosticosToInternamento';
import AddComplicacoesToInternamento from '../Complicacoes/AddComplicacoesToInternamento';
import CreateOrUpdateUtente from '../Utentes/CreateOrUpdateUtente';
import CreateOrUpdateInternamentoModal from './CreateOrUpdateInternamentoModal';
import { InternamentoOptions } from '@/types/internamento';
import type { CentroDeReferenciaItem, DestinoItem, OrigemDoInternamentoItem, User, UtenteItem } from '@/types/type';

type CentroDeReferenciaOptions = {
    origens: OrigemDoInternamentoItem[];
    destinos: DestinoItem[];
    responsaveis: User[];
};

type Props = {
    internamento: any;
    utente: UtenteItem;
    centroDeReferencia: CentroDeReferenciaItem | null;
    diagnosticosAgrupados: Record<string, any[]>;
    complicacoesAgrupadas: Record<string, any[]>;
    internamentoOptions: InternamentoOptions;
    centroDeReferenciaOptions: CentroDeReferenciaOptions;
};

const breadcrumbs = [
    { title: 'Internamentos', href: route('internamentos.index') },
    { title: 'Detalhes do Internamento', href: '' },
];

export default function ShowInternamento({
    internamento,
    utente,
    centroDeReferencia,
    internamentoOptions,
    centroDeReferenciaOptions,
    diagnosticosAgrupados,
    complicacoesAgrupadas,
}: Props) {
    const [showCentroModal, setShowCentroModal] = useState(false);
    const [isOpenUtente, setIsOpenUtente] = useState(false);
    const [isInternamentoOpen, setIsInternamentoOpen] = useState(false);

    const openCentroModal = () => setShowCentroModal(true);
    const closeCentroModal = () => setShowCentroModal(false);
    const [showDiagnosticosToInternamentoModal, setShowDiagnosticosToInternamentoModal] = useState(false);
    const [showComplicacoesToInternamentoModal, setShowComplicacoesToInternamentoModal] = useState(false);

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
                            { label: 'N.º de Cirurgias', value: internamento.bloco_operatorios_count ?? '—' },
                            { label: 'Bloco Operatório', value: internamento.bloco_operatorios.map((bloco: any) => bloco.data_de_inicio).join(', ') || '—' },
                        ]}
                    />
                    {/* Funcionalidade de Cirurgias/Bloco Operatório ainda não existe no backend. */}

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
                        fields={[{ label: 'N.º de Complicações', value: internamento.complicacaos_count ?? 0 }]}
                        action={
                            <div className="flex flex-col gap-2 p-3">
                                <Button variant="outline" onClick={() => setShowComplicacoesToInternamentoModal(true)}>
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
                        origens={centroDeReferenciaOptions.origens}
                        destinos={centroDeReferenciaOptions.destinos}
                        responsaveis={centroDeReferenciaOptions.responsaveis}
                        onSuccess={() => {
                            closeCentroModal();
                            router.reload({ only: ['centroDeReferencia'] });
                        }}
                    />

                    {/* Modal internamento */}

                    <CreateOrUpdateInternamentoModal
                        open={isInternamentoOpen}
                        internamento={internamento}
                        utenteId={internamento.utente?.id ?? null}
                        onClose={() => setIsInternamentoOpen(false)}
                        internamentoOptions={internamentoOptions}
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
                    {/* Modal complicações */}
                    {showComplicacoesToInternamentoModal && (
                        <AddComplicacoesToInternamento
                            internamento={internamento}
                            complicacoesAgrupadas={complicacoesAgrupadas}
                            onClose={() => setShowComplicacoesToInternamentoModal(false)}
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
