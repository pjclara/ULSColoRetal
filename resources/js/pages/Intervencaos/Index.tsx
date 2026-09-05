import { Head } from '@inertiajs/react';

type IntervencaoItem = {
    id: number;
    created_at?: string | null;
    updated_at?: string | null;
};

type Props = {
    intervencaos: {
        data: IntervencaoItem[];
    };
};

export default function Index({ intervencaos }: Props) {
    return (
        <>
            <Head title="Intervencaos" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    Intervencaos
                </h1>

                <div className="mt-6">
                    {intervencaos.data.length === 0 ? (
                        <p>Nenhum registo encontrado.</p>
                    ) : (
                        <ul>
                            {intervencaos.data.map((item) => (
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