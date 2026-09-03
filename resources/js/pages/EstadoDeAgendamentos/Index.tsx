import { Head } from '@inertiajs/react';

type EstadoDeAgendamentoItem = {
    id: number;
    created_at?: string | null;
    updated_at?: string | null;
};

type Props = {
    estadoDeAgendamentos: {
        data: EstadoDeAgendamentoItem[];
    };
};

export default function Index({ estadoDeAgendamentos }: Props) {
    return (
        <>
            <Head title="EstadoDeAgendamentos" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    EstadoDeAgendamentos
                </h1>

                <div className="mt-6">
                    {estadoDeAgendamentos.data.length === 0 ? (
                        <p>Nenhum registo encontrado.</p>
                    ) : (
                        <ul>
                            {estadoDeAgendamentos.data.map((item) => (
                                <li key={item.id}>
                                    #{item.id}
                                </li>
                            ))}
                        </ul>
                    )}
                </div>
            </div>
        </>
    );
}