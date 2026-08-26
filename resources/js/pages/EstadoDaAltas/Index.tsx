import { Head } from '@inertiajs/react';

type EstadoDaAltaItem = {
    id: number;
    created_at?: string | null;
    updated_at?: string | null;
};

type Props = {
    estadoDaAltas: {
        data: EstadoDaAltaItem[];
    };
};

export default function Index({ estadoDaAltas }: Props) {
    return (
        <>
            <Head title="EstadoDaAltas" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    EstadoDaAltas
                </h1>

                <div className="mt-6">
                    {estadoDaAltas.data.length === 0 ? (
                        <p>Nenhum registo encontrado.</p>
                    ) : (
                        <ul>
                            {estadoDaAltas.data.map((item) => (
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