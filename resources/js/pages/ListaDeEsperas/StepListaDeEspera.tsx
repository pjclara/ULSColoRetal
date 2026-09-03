import { AppEmptyState } from '@/components/app/app-empty-state';
import { AppEntitySummary } from '@/components/app/app-entity-summary';
import { AppTable, AppTableColumn } from '@/components/app/app-table';
import { Button } from '@/components/ui/button';
import type { CentroDeReferenciaItem, ListaDeEsperaItem, Option, UtenteItem } from '@/types/type';
import { useState } from 'react';
import CreateOrUpdateListaDeEspera from './CreateOrUpdateListaDeEspera';

type Props = {
    utente: UtenteItem;
    centroDeReferencia?: CentroDeReferenciaItem | null;
    estadoOptions: Option[];
    responsavelOptions: Option[];
    onContinue: () => void;
    onBack: () => void;
};

const columns: AppTableColumn<ListaDeEsperaItem>[] = [
    {
        label: 'Data de lista',
        key: 'data_de_lista',
    },
    {
        label: 'Estado da lista de espera',
        key: 'estado_lista_espera',
    },
    {
        label: 'Cancelar lista de espera',
        key: 'cancelar_lista_espera',
    },
    {
        label: 'Comentários',
        key: 'comentarios',
    },
    {
        label: 'Responsável',
        key: 'responsavel',
        render: (listaDeEsperaItem) => listaDeEsperaItem.responsavel?.name ?? '',
    },
];

export default function StepListaDeEspera({
    utente,
    centroDeReferencia,
    estadoOptions,
    responsavelOptions,
    onBack,
    onContinue,
}: Props) {
    const [showCreateListaDeEsperaModal, setShowCreateListaDeEsperaModal] = useState(false);
    const handleCreateListaDeEsperaSuccess = () => {
        setShowCreateListaDeEsperaModal(false);
        onContinue();
    };
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
                <AppEmptyState
                    title="Centro de referência"
                    description="Não foi selecionado nenhum centro de referência para este utente."
                    actions={[
                        {
                            label: 'Voltar',
                            onClick: onBack,
                        },
                        {
                            label: 'Continuar',
                            onClick: onContinue,
                        },
                    ]}
                />
            )}
            {utente.lista_de_esperas?.length === 0 && (
                <AppEmptyState
                    title="Listas de espera"
                    description="Não há listas de espera para este utente."
                    actions={[
                        {
                            label: 'Adicionar lista de espera',
                            onClick: () => setShowCreateListaDeEsperaModal(true),
                        },
                    ]}
                />
            )}

            {utente.lista_de_esperas && utente.lista_de_esperas.length > 0 && <AppTable columns={columns} data={utente.lista_de_esperas} />}

            {showCreateListaDeEsperaModal && (
                <CreateOrUpdateListaDeEspera
                    isOpenListaDeEspera={showCreateListaDeEsperaModal}
                    utenteId={utente.id}
                    onClose={() => setShowCreateListaDeEsperaModal(false)}
                    onSuccess={handleCreateListaDeEsperaSuccess}
                    estadoOptions={estadoOptions}
                    responsavelOptions={responsavelOptions}
                />
            )}
        </div>
    );
}
