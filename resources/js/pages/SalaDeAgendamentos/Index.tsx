import { Head } from '@inertiajs/react';

type SalaDeAgendamentoItem = {
    id: number;
    created_at?: string | null;
    updated_at?: string | null;
};

type Props = {
    salaDeAgendamentos: {
        data: SalaDeAgendamentoItem[];
    };
};

export default function Index({ salaDeAgendamentos }: Props) {
    return (
        <>
            <Head title="SalaDeAgendamentos" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    SalaDeAgendamentos
                </h1>

                <div className="mt-6">
                    {salaDeAgendamentos.data.length === 0 ? (
                        <p>Nenhum registo encontrado.</p>
                    ) : (
                        <ul>
                            {salaDeAgendamentos.data.map((item) => (
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