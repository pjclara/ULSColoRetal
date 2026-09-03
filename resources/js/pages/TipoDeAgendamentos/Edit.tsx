import { Head } from '@inertiajs/react';

type Props = {
    tipoDeAgendamento: {
        id: number;
    };
};

export default function Edit({ tipoDeAgendamento }: Props) {
    return (
        <>
            <Head title="Editar TipoDeAgendamento" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    Editar TipoDeAgendamento #{tipoDeAgendamento.id}
                </h1>
            </div>
        </>
    );
}