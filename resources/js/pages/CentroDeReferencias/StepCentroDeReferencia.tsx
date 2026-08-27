import { AppEntitySummary } from '@/components/app/app-entity-summary';
import { Button } from '@/components/ui/button';
import { InternamentoOptions } from '@/types/internamento';

import type { CentroDeReferenciaItem, UtenteItem } from '@/types/type';

import { useEffect, useState } from 'react';
import CentroDeReferenciaModal from './CentroDeReferenciaModal';

type Props = {
    utente: UtenteItem;
    centroDeReferencia?: CentroDeReferenciaItem | null;
    internamentoOptions: InternamentoOptions;
    onBack: () => void;
    onContinue: () => void;
    onSuccess?: (centro: CentroDeReferenciaItem) => void;
};

const emptyForm: CentroDeReferenciaItem = {
    utente_id: 0,
    data_de_diagnostico: '',
    data_de_referenciacao: '',
    origem_id: 0,
    data_de_entrada: '',
    data_de_saida: '',
    destino_id: 0,
    responsavel_id: 0,
    comentarios: '',
};

export default function CreateOrUpdateCentroDeReferencia({ utente, centroDeReferencia, onBack, onContinue, internamentoOptions }: Props) {
    const [centroDeReferenciaData, setCentroDeReferencia] = useState<CentroDeReferenciaItem | null>(centroDeReferencia ?? null);
    const [showCentroModal, setShowCentroModal] = useState(false);

    const handleCentroDeReferenciaSuccess = (centro: CentroDeReferenciaItem) => {
        setCentroDeReferencia(centro);
        setShowCentroModal(false);
    };

    useEffect(() => {
        setCentroDeReferencia(centroDeReferencia ?? null);
    }, [centroDeReferencia]);
    return (
        <div className="space-y-6">
            <div>
                <h2 className="text-xl font-semibold">Selecionar internamento</h2>

                <p className="mt-1 text-sm text-neutral-500">Selecione um internamento existente para o utente ou crie um novo.</p>
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
            {centroDeReferenciaData ? (
                <AppEntitySummary
                    title="Centro de referência"
                    fields={[
                        {
                            label: 'Referenciação',
                            value: centroDeReferenciaData.data_de_referenciacao ?? '',
                        },
                        {
                            label: 'Diagnóstico',
                            value: centroDeReferenciaData.data_de_diagnostico ?? '',
                        },
                        {
                            label: 'Origem',
                            value: centroDeReferenciaData.origem?.nome ?? '',
                        },
                        {
                            label: 'Comentários',
                            value: centroDeReferenciaData.comentarios ?? '',
                        },
                    ]}
                    action={
                        <div className="mt-4 flex shrink-0 justify-between gap-2">
                            <Button type="button" onClick={() => setShowCentroModal(true)}>
                                Editar centro de referência
                            </Button>
                            <Button type="button" variant="outline" onClick={onContinue}>
                                Continuar
                            </Button>
                        </div>
                    }
                />
            ) : (
                <div className="rounded-lg border p-6">
                    <h2 className="text-lg font-semibold">Centro de referência</h2>
                    <p className="text-muted-foreground mt-1 text-sm">
                        Nenhum centro de referência selecionado. Por favor, crie um novo centro de referência para continuar.
                    </p>
                    <div className="mt-4 flex shrink-0 justify-between gap-2">
                        <Button type="button" className="ml-2" onClick={() => setShowCentroModal(true)}>
                            Criar centro de referência
                        </Button>
                        <Button type="button" variant="outline" onClick={onContinue}>
                            Continuar
                        </Button>
                    </div>
                </div>
            )}
            <CentroDeReferenciaModal
                open={showCentroModal}
                onClose={() => setShowCentroModal(false)}
                utente={utente}
                centroDeReferencia={centroDeReferenciaData}
                origens={internamentoOptions.origensDaReferenciacao}
                destinos={internamentoOptions.destinos}
                responsaveis={internamentoOptions.responsaveis}
                onSuccess={handleCentroDeReferenciaSuccess}
            />
        </div>
    );
}
