import { Head } from '@inertiajs/react';

type Props = {
    utente: {
        id: number;
    };
};

export default function Show({ utente }: Props) {
    return (
        <>
            <Head title="Utente" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    Utente #{utente.id}
                </h1>
            </div>
        </>
    );
}