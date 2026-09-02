import { Head } from '@inertiajs/react';

type Props = {
    listaDeEspera: {
        id: number;
    };
};

export default function Show({ listaDeEspera }: Props) {
    return (
        <>
            <Head title="ListaDeEspera" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    ListaDeEspera #{listaDeEspera.id}
                </h1>
            </div>
        </>
    );
}