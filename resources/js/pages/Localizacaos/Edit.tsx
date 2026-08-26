import { Head } from '@inertiajs/react';

type Props = {
    localizacao: {
        id: number;
    };
};

export default function Edit({ localizacao }: Props) {
    return (
        <>
            <Head title="Editar Localizacao" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    Editar Localizacao #{localizacao.id}
                </h1>
            </div>
        </>
    );
}