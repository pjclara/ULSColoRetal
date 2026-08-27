import { Head } from '@inertiajs/react';

type Props = {
    centroDeReferencia: {
        id: number;
    };
};

export default function Edit({ centroDeReferencia }: Props) {
    return (
        <>
            <Head title="Editar CentroDeReferencia" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    Editar CentroDeReferencia #{centroDeReferencia.id}
                </h1>
            </div>
        </>
    );
}