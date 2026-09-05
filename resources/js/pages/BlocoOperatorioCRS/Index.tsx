import { Head } from '@inertiajs/react';

type BlocoOperatorioCRSItem = {
    id: number;
    created_at?: string | null;
    updated_at?: string | null;
};

type Props = {
    blocoOperatorioCRSs: {
        data: BlocoOperatorioCRSItem[];
    };
};

export default function Index({ blocoOperatorioCRSs }: Props) {
    return (
        <>
            <Head title="BlocoOperatorioCRS" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    BlocoOperatorioCRS
                </h1>

                <div className="mt-6">
                    {blocoOperatorioCRSs.data.length === 0 ? (
                        <p>Nenhum registo encontrado.</p>
                    ) : (
                        <ul>
                            {blocoOperatorioCRSs.data.map((item) => (
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