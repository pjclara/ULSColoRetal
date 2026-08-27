import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import CentroDeReferenciaForm from './CentroDeReferenciaForm';

import type { CentroDeReferenciaItem, DestinoItem, OrigemDoInternamentoItem, User, UtenteItem } from '@/types/type';

type Props = {
    open: boolean;
    onClose: () => void;

    utente: UtenteItem;
    centroDeReferencia?: CentroDeReferenciaItem | null;

    origens: OrigemDoInternamentoItem[];
    destinos: DestinoItem[];
    responsaveis: User[];

    onSuccess?: (centro: CentroDeReferenciaItem) => void;
};

export default function CentroDeReferenciaModal({ open, onClose, utente, centroDeReferencia, origens, destinos, responsaveis, onSuccess }: Props) {
    const editing = !!centroDeReferencia;

    return (
        <Dialog open={open} onOpenChange={onClose}>
            <DialogContent className="max-w-3xl">
                <DialogHeader>
                    <DialogTitle>{editing ? 'Editar Centro de Referência' : 'Criar Centro de Referência'}</DialogTitle>
                </DialogHeader>

                <CentroDeReferenciaForm
                    utente={utente}
                    centroDeReferencia={centroDeReferencia}
                    origens={origens}
                    destinos={destinos}
                    responsaveis={responsaveis}
                    onBack={onClose}
                    onClose={onClose}
                    onSuccess={onSuccess}
                />
            </DialogContent>
        </Dialog>
    );
}
