import { Head } from '@inertiajs/react';

type ComplicacaoItem = {
    id: number;
    created_at?: string | null;
    updated_at?: string | null;
};

type Props = {
    complicacaos: {
        data: ComplicacaoItem[];
    };
};

export default function Index({ complicacaos }: Props) {
    return (
        <>
            <Head title="Complicacaos" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    Complicacaos
                </h1>

                <div className="mt-6">
                    {complicacaos.data.length === 0 ? (
                        <p>Nenhum registo encontrado.</p>
                    ) : (
                        <ul>
                            {complicacaos.data.map((item) => (
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