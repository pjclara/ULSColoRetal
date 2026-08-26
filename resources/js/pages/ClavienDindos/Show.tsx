import { Head } from '@inertiajs/react';

type Props = {
    clavienDindo: {
        id: number;
    };
};

export default function Show({ clavienDindo }: Props) {
    return (
        <>
            <Head title="ClavienDindo" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    ClavienDindo #{clavienDindo.id}
                </h1>
            </div>
        </>
    );
}