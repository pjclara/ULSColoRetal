import { AppEmptyState } from '@/components/app/app-empty-state';
import { AppEntitySummary } from '@/components/app/app-entity-summary';
import { AppTable, AppTableColumn } from '@/components/app/app-table';
import { Button } from '@/components/ui/button';
import type { AgendamentoItem, CentroDeReferenciaItem, ListaDeEsperaItem, Option, UtenteItem } from '@/types/type';
import { useState } from 'react';
import CreateOrUpdateAgendamento from './CreateOrUpdateAgendamento';

type Props = {
    utente: UtenteItem;
    centroDeReferencia?: CentroDeReferenciaItem | null;
    listaDeEspera: ListaDeEsperaItem;
    responsavelOptions: Option[];
    tipoDeAgendamentoOptions: Option[];
    localDeAgendamentoOptions: Option[];
    salaDeAgendamentoOptions: Option[];
    periodoDeAgendamentoOptions: Option[];
    estadoDeAgendamentoOptions: Option[];
    onContinue: () => void;
    onBack: () => void;
};

export default function StepAgendamentos({
    utente,
    centroDeReferencia,
    listaDeEspera,
    responsavelOptions,
    tipoDeAgendamentoOptions,
    localDeAgendamentoOptions,
    salaDeAgendamentoOptions,
    periodoDeAgendamentoOptions,
    estadoDeAgendamentoOptions,
    onBack,
    onContinue,
}: Props) {
    const [agendamentos, setAgendamentos] = useState<AgendamentoItem[]>(listaDeEspera.agendamentos ?? []);
    const [showAgendamentoModal, setShowAgendamentoModal] = useState(false);
    const [currentAgendamento, setCurrentAgendamento] = useState<AgendamentoItem | null>(null);

    const handleAgendamentoSuccess = (agendamento?: AgendamentoItem) => {
        setShowAgendamentoModal(false);

        if (!agendamento) {
            return;
        }

        setAgendamentos((current) => {
            const existeIndex = current.findIndex((item) => item.id === agendamento.id);

            if (existeIndex === -1) {
                return [...current, agendamento];
            }

            return current.map((item, index) => (index === existeIndex ? agendamento : item));
        });
    };

    const columns: AppTableColumn<AgendamentoItem>[] = [
        {
            label: 'Data',
            key: 'data',
            render: (agendamentoItem) => agendamentoItem.start ?? '',
        },

        {
            label: 'Comentários',
            key: 'comentarios',
        },
        {
            label: 'Ações',
            key: 'acoes',
            render: (agendamentoItem) => (
                <Button
                    type="button"
                    size="sm"
                    variant="ghost"
                    onClick={() => {
                        setCurrentAgendamento(agendamentoItem);
                        setShowAgendamentoModal(true);
                    }}
                >
                    Editar
                </Button>
            ),
        },
    ];

    return (
        <div className="space-y-6">
            <div>
                <h2 className="text-xl font-semibold">Agendamento</h2>

                <p className="mt-1 text-sm text-neutral-500">
                    Confirme os dados recolhidos e indique se pretende agendar já uma consulta para este utente.
                </p>
            </div>
            <AppEntitySummary
                title="Utente selecionado"
                fields={[
                    {
                        label: 'Nome',
                        value: utente.nome,
                    },
                    {
                        label: 'N.º Processo',
                        value: utente.numero_processo ?? '',
                    },
                    {
                        label: 'Data de nascimento',
                        value: utente.data_nascimento ?? '',
                    },
                ]}
                action={
                    <div className="mt-4 flex shrink-0 justify-end gap-2">
                        <Button type="button" variant="outline" onClick={onBack}>
                            Alterar utente
                        </Button>
                    </div>
                }
            />
            {centroDeReferencia ? (
                <AppEntitySummary
                    title="Centro de referência"
                    fields={[
                        {
                            label: 'Referenciação',
                            value: centroDeReferencia.data_de_referenciacao ?? '',
                        },
                        {
                            label: 'Diagnóstico',
                            value: centroDeReferencia.data_de_diagnostico ?? '',
                        },
                        {
                            label: 'Origem',
                            value: centroDeReferencia.origem?.nome ?? '',
                        },
                        {
                            label: 'Comentários',
                            value: centroDeReferencia.comentarios ?? '',
                        },
                    ]}
                />
            ) : (
                <AppEmptyState title="Centro de referência" description="Utente não faz parte do centro de referência." />
            )}
            {listaDeEspera ? (
                <AppEntitySummary
                    title="Lista de espera"
                    fields={[
                        {
                            label: 'Data de lista',
                            value: listaDeEspera.data_de_lista ?? '',
                        },
                        {
                            label: 'Estado',
                            value: listaDeEspera.estado_lista_espera ?? '',
                        },
                        {
                            label: 'Cancelar',
                            value: listaDeEspera.cancelar_lista_espera ? 'Sim' : 'Não',
                        },
                        {
                            label: 'Comentários',
                            value: listaDeEspera.comentarios ?? '',
                        },
                    ]}
                />
            ) : (
                <AppEmptyState title="Lista de espera" description="Utente não faz parte da lista de espera." />
            )}

            {agendamentos.length === 0 ? (
                <AppEmptyState
                    title="Ainda não há agendamentos para este utente."
                    description="Deseja agendar já uma consulta? Pode continuar sem agendar e fazê-lo mais tarde."
                    actions={[
                        {
                            label: 'Sim, agendar agora',
                            onClick: () => {
                                setCurrentAgendamento(null);
                                setShowAgendamentoModal(true);
                            },
                        },
                        {
                            label: 'Não, continuar sem agendar',
                            variant: 'outline',
                            onClick: onContinue,
                        },
                    ]}
                />
            ) : (
                <div>
                    <div className="mb-4 flex justify-end">
                        <Button
                            type="button"
                            variant="info"
                            onClick={() => {
                                setCurrentAgendamento(null);
                                setShowAgendamentoModal(true);
                            }}
                        >
                            Adicionar outro agendamento
                        </Button>
                    </div>
                    <AppTable columns={columns} data={agendamentos} />
                </div>
            )}

            <div className="flex justify-end gap-2">
                <Button type="button" variant="outline" onClick={onBack}>
                    Voltar à lista de espera
                </Button>
                <Button type="button" onClick={onContinue}>
                    Continuar para confirmação
                </Button>
            </div>

            <CreateOrUpdateAgendamento
                agendamento={currentAgendamento}
                isOpenAgendamento={showAgendamentoModal}
                listaDeEsperaId={listaDeEspera.id}
                onClose={() => setShowAgendamentoModal(false)}
                onSuccess={handleAgendamentoSuccess}
                responsavelOptions={responsavelOptions}
                tipoDeAgendamentoOptions={tipoDeAgendamentoOptions}
                localDeAgendamentoOptions={localDeAgendamentoOptions}
                salaDeAgendamentoOptions={salaDeAgendamentoOptions}
                periodoDeAgendamentoOptions={periodoDeAgendamentoOptions}
                estadoDeAgendamentoOptions={estadoDeAgendamentoOptions}
            />
        </div>
    );
}
