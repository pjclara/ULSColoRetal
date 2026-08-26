import { Head } from '@inertiajs/react';

type UtenteItem = {
    id: number;
    created_at?: string | null;
    updated_at?: string | null;
};

type Props = {
    utentes: {
        data: UtenteItem[];
    };
};

export default function Index({ utentes }: Props) {
    return (
        <>
            <Head title="Utentes" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    Utentes
                </h1>

                <div className="mt-6">
                    {utentes.data.length === 0 ? (
                        <p>Nenhum registo encontrado.</p>
                    ) : (
                        <ul>
                            {utentes.data.map((item) => (
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