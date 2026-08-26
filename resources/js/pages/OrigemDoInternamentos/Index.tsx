import { Head } from '@inertiajs/react';

type OrigemDoInternamentoItem = {
    id: number;
    created_at?: string | null;
    updated_at?: string | null;
};

type Props = {
    origemDoInternamentos: {
        data: OrigemDoInternamentoItem[];
    };
};

export default function Index({ origemDoInternamentos }: Props) {
    return (
        <>
            <Head title="OrigemDoInternamentos" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    OrigemDoInternamentos
                </h1>

                <div className="mt-6">
                    {origemDoInternamentos.data.length === 0 ? (
                        <p>Nenhum registo encontrado.</p>
                    ) : (
                        <ul>
                            {origemDoInternamentos.data.map((item) => (
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