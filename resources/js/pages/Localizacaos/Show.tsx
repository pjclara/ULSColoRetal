import { Head } from '@inertiajs/react';

type Props = {
    localizacao: {
        id: number;
    };
};

export default function Show({ localizacao }: Props) {
    return (
        <>
            <Head title="Localizacao" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    Localizacao #{localizacao.id}
                </h1>
            </div>
        </>
    );
}