import { Head } from '@inertiajs/react';

type Props = {
    listaDeEspera: {
        id: number;
    };
};

export default function Edit({ listaDeEspera }: Props) {
    return (
        <>
            <Head title="Editar ListaDeEspera" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    Editar ListaDeEspera #{listaDeEspera.id}
                </h1>
            </div>
        </>
    );
}