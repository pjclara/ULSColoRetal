import { Head } from '@inertiajs/react';

type Props = {
    destino: {
        id: number;
    };
};

export default function Edit({ destino }: Props) {
    return (
        <>
            <Head title="Editar Destino" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    Editar Destino #{destino.id}
                </h1>
            </div>
        </>
    );
}