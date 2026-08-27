import { Head } from '@inertiajs/react';

type CentroDeReferenciaItem = {
    id: number;
    created_at?: string | null;
    updated_at?: string | null;
};

type Props = {
    centroDeReferencias: {
        data: CentroDeReferenciaItem[];
    };
};

export default function Index({ centroDeReferencias }: Props) {
    return (
        <>
            <Head title="CentroDeReferencias" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    CentroDeReferencias
                </h1>

                <div className="mt-6">
                    {centroDeReferencias.data.length === 0 ? (
                        <p>Nenhum registo encontrado.</p>
                    ) : (
                        <ul>
                            {centroDeReferencias.data.map((item) => (
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