import { Head } from '@inertiajs/react';

type TipoDeAgendamentoItem = {
    id: number;
    created_at?: string | null;
    updated_at?: string | null;
};

type Props = {
    tipoDeAgendamentos: {
        data: TipoDeAgendamentoItem[];
    };
};

export default function Index({ tipoDeAgendamentos }: Props) {
    return (
        <>
            <Head title="TipoDeAgendamentos" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    TipoDeAgendamentos
                </h1>

                <div className="mt-6">
                    {tipoDeAgendamentos.data.length === 0 ? (
                        <p>Nenhum registo encontrado.</p>
                    ) : (
                        <ul>
                            {tipoDeAgendamentos.data.map((item) => (
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