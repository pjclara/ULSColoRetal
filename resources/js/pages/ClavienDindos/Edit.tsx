import { Head } from '@inertiajs/react';

type Props = {
    clavienDindo: {
        id: number;
    };
};

export default function Edit({ clavienDindo }: Props) {
    return (
        <>
            <Head title="Editar ClavienDindo" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    Editar ClavienDindo #{clavienDindo.id}
                </h1>
            </div>
        </>
    );
}