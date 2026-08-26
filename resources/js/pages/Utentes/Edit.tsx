import { Head } from '@inertiajs/react';

type Props = {
    utente: {
        id: number;
    };
};

export default function Edit({ utente }: Props) {
    return (
        <>
            <Head title="Editar Utente" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    Editar Utente #{utente.id}
                </h1>
            </div>
        </>
    );
}