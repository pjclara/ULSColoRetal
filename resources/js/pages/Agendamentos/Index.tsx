import { Head } from '@inertiajs/react';

type AgendamentoItem = {
    id: number;
    created_at?: string | null;
    updated_at?: string | null;
};

type Props = {
    agendamentos: {
        data: AgendamentoItem[];
    };
};

export default function Index({ agendamentos }: Props) {
    return (
        <>
            <Head title="Agendamentos" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    Agendamentos
                </h1>

                <div className="mt-6">
                    {agendamentos.data.length === 0 ? (
                        <p>Nenhum registo encontrado.</p>
                    ) : (
                        <ul>
                            {agendamentos.data.map((item) => (
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