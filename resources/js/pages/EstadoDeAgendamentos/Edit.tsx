import { Head } from '@inertiajs/react';

type Props = {
    estadoDeAgendamento: {
        id: number;
    };
};

export default function Edit({ estadoDeAgendamento }: Props) {
    return (
        <>
            <Head title="Editar EstadoDeAgendamento" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    Editar EstadoDeAgendamento #{estadoDeAgendamento.id}
                </h1>
            </div>
        </>
    );
}