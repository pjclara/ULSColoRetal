import { Head } from '@inertiajs/react';

type DestinoItem = {
    id: number;
    created_at?: string | null;
    updated_at?: string | null;
};

type Props = {
    destinos: {
        data: DestinoItem[];
    };
};

export default function Index({ destinos }: Props) {
    return (
        <>
            <Head title="Destinos" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    Destinos
                </h1>

                <div className="mt-6">
                    {destinos.data.length === 0 ? (
                        <p>Nenhum registo encontrado.</p>
                    ) : (
                        <ul>
                            {destinos.data.map((item) => (
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