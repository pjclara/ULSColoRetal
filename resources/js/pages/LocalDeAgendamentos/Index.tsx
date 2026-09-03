import { Head } from '@inertiajs/react';

type LocalDeAgendamentoItem = {
    id: number;
    created_at?: string | null;
    updated_at?: string | null;
};

type Props = {
    localDeAgendamentos: {
        data: LocalDeAgendamentoItem[];
    };
};

export default function Index({ localDeAgendamentos }: Props) {
    return (
        <>
            <Head title="LocalDeAgendamentos" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    LocalDeAgendamentos
                </h1>

                <div className="mt-6">
                    {localDeAgendamentos.data.length === 0 ? (
                        <p>Nenhum registo encontrado.</p>
                    ) : (
                        <ul>
                            {localDeAgendamentos.data.map((item) => (
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