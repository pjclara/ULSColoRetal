import { UtenteItem } from '@/types/type';
import { FormEvent, useState } from 'react';

type UtenteFormProps = {
	initialData?: Partial<UtenteItem>;
	onSubmit: (data: UtenteItem) => void | Promise<void>;
	submitLabel?: string;
	cancelLabel?: string;
	onCancel?: () => void;
	processing?: boolean;
};

const emptyData: UtenteItem = {
    id: 0,
    nome: '',
    data_nascimento: '',
    sexo_id: '',
    numero_utente: null,
    numero_processo: null,
    concelho_id: null,
};

export default function UtenteForm({
	initialData,
	onSubmit,
	submitLabel = 'Guardar utente',
	cancelLabel = 'Cancelar',
	onCancel,
	processing = false,
}: UtenteFormProps) {
	const [data, setData] = useState<UtenteItem>({ ...emptyData, ...initialData });
	const [errors, setErrors] = useState<Partial<Record<keyof UtenteItem, string>>>({});

	const updateField = (field: keyof UtenteItem, value: string) => {
		setData((current) => ({ ...current, [field]: value }));
		setErrors((current) => ({ ...current, [field]: undefined }));
	};

	const handleSubmit = async (event: FormEvent<HTMLFormElement>) => {
		event.preventDefault();
		const nextErrors: Partial<Record<keyof UtenteItem, string>> = {};

		
	};

	return (
		<form onSubmit={handleSubmit} className="space-y-6" noValidate>
			
		</form>
	);
}
