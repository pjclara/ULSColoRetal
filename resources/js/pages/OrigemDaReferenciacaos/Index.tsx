import { Head } from '@inertiajs/react';

type OrigemDaReferenciacaoItem = {
    id: number;
    created_at?: string | null;
    updated_at?: string | null;
};

type Props = {
    origemDaReferenciacaos: {
        data: OrigemDaReferenciacaoItem[];
    };
};

export default function Index({ origemDaReferenciacaos }: Props) {
    return (
        <>
            <Head title="OrigemDaReferenciacaos" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    OrigemDaReferenciacaos
                </h1>

                <div className="mt-6">
                    {origemDaReferenciacaos.data.length === 0 ? (
                        <p>Nenhum registo encontrado.</p>
                    ) : (
                        <ul>
                            {origemDaReferenciacaos.data.map((item) => (
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