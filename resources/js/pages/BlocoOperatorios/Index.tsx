import { Head } from '@inertiajs/react';

type BlocoOperatorioItem = {
    id: number;
    created_at?: string | null;
    updated_at?: string | null;
};

type Props = {
    blocoOperatorios: {
        data: BlocoOperatorioItem[];
    };
};

export default function Index({ blocoOperatorios }: Props) {
    return (
        <>
            <Head title="BlocoOperatorios" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    BlocoOperatorios
                </h1>

                <div className="mt-6">
                    {blocoOperatorios.data.length === 0 ? (
                        <p>Nenhum registo encontrado.</p>
                    ) : (
                        <ul>
                            {blocoOperatorios.data.map((item) => (
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