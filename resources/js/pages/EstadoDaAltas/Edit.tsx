import { Head } from '@inertiajs/react';

type Props = {
    estadoDaAlta: {
        id: number;
    };
};

export default function Edit({ estadoDaAlta }: Props) {
    return (
        <>
            <Head title="Editar EstadoDaAlta" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    Editar EstadoDaAlta #{estadoDaAlta.id}
                </h1>
            </div>
        </>
    );
}