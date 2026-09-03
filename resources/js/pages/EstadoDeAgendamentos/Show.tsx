import { Head } from '@inertiajs/react';

type Props = {
    estadoDeAgendamento: {
        id: number;
    };
};

export default function Show({ estadoDeAgendamento }: Props) {
    return (
        <>
            <Head title="EstadoDeAgendamento" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    EstadoDeAgendamento #{estadoDeAgendamento.id}
                </h1>
            </div>
        </>
    );
}