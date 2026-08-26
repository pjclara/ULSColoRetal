import { Head } from '@inertiajs/react';

type ClavienDindoItem = {
    id: number;
    created_at?: string | null;
    updated_at?: string | null;
};

type Props = {
    clavienDindos: {
        data: ClavienDindoItem[];
    };
};

export default function Index({ clavienDindos }: Props) {
    return (
        <>
            <Head title="ClavienDindos" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    ClavienDindos
                </h1>

                <div className="mt-6">
                    {clavienDindos.data.length === 0 ? (
                        <p>Nenhum registo encontrado.</p>
                    ) : (
                        <ul>
                            {clavienDindos.data.map((item) => (
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