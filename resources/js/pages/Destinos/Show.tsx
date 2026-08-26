import { Head } from '@inertiajs/react';

type Props = {
    destino: {
        id: number;
    };
};

export default function Show({ destino }: Props) {
    return (
        <>
            <Head title="Destino" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    Destino #{destino.id}
                </h1>
            </div>
        </>
    );
}