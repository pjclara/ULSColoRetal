import { Head } from '@inertiajs/react';

type Props = {
    diagnostico: {
        id: number;
    };
};

export default function Edit({ diagnostico }: Props) {
    return (
        <>
            <Head title="Editar Diagnostico" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    Editar Diagnostico #{diagnostico.id}
                </h1>
            </div>
        </>
    );
}