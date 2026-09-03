import { AppEmptyState } from '@/components/app/app-empty-state';
import { AppEntitySummary } from '@/components/app/app-entity-summary';
import { AppTable, AppTableColumn } from '@/components/app/app-table';
import { Button } from '@/components/ui/button';
import type { AgendamentoItem, CentroDeReferenciaItem, ListaDeEsperaItem, UtenteItem } from '@/types/type';

type Props = {
    utente: UtenteItem;
    centroDeReferencia?: CentroDeReferenciaItem | null;
    listaDeEspera: ListaDeEsperaItem;
    agendamentos?: AgendamentoItem[] | null;
    onContinue: () => void;
    onBack: () => void;
};

export default function StepAgendamentos({
    utente,
    centroDeReferencia,
    listaDeEspera,
    onBack,
    onContinue,
}: Props) {
    const agendamentos = listaDeEspera.agendamentos ?? [];

    const columns: AppTableColumn<NonNullable<ListaDeEsperaItem['agendamentos']>[number]>[] = [
        {
            label: 'Data',
            key: 'data',
            render: (agendamentoItem) => agendamentoItem.start ?? '',
        },

        {
            label: 'Comentários',
            key: 'comentarios',
        },
    ];
    return (
        <div className="space-y-6">
            <div>
                <h2 className="text-xl font-semibold">Lista de espera</h2>

                <p className="mt-1 text-sm text-neutral-500">
                    Confirme o utente e o respetivo centro de referência antes de preencher os dados da lista de espera.
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
                    title="Agendamentos"
                    description="Não há agendamentos para este utente. Pode continuar para a confirmação."
                />
            ) : (
                <div>
                    <div className="mb-4 flex justify-end">
                        <span className="text-sm text-neutral-500">Agendamentos existentes</span>
                    </div>
                    <AppTable columns={columns} data={agendamentos} />
                </div>
            )}

            <div className="flex justify-end">
                <Button type="button" variant="outline" onClick={onBack}>
                    Voltar à lista de espera
                </Button>
                <Button type="button" className="ml-2" onClick={onContinue}>
                    Continuar para confirmação
                </Button>
            </div>
        </div>
    );
}
