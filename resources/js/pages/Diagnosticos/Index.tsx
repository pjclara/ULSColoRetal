import { Head } from '@inertiajs/react';

type DiagnosticoItem = {
    id: number;
    created_at?: string | null;
    updated_at?: string | null;
};

type Props = {
    diagnosticos: {
        data: DiagnosticoItem[];
    };
};

export default function Index({ diagnosticos }: Props) {
    return (
        <>
            <Head title="Diagnosticos" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    Diagnosticos
                </h1>

                <div className="mt-6">
                    {diagnosticos.data.length === 0 ? (
                        <p>Nenhum registo encontrado.</p>
                    ) : (
                        <ul>
                            {diagnosticos.data.map((item) => (
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