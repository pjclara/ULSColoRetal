import { Head } from '@inertiajs/react';

type Props = {
    centroDeReferencia: {
        id: number;
    };
};

export default function Show({ centroDeReferencia }: Props) {
    return (
        <>
            <Head title="CentroDeReferencia" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    CentroDeReferencia #{centroDeReferencia.id}
                </h1>
            </div>
        </>
    );
}