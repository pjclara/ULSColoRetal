import { Head } from '@inertiajs/react';

type Props = {
    complicacao: {
        id: number;
    };
};

export default function Edit({ complicacao }: Props) {
    return (
        <>
            <Head title="Editar Complicacao" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    Editar Complicacao #{complicacao.id}
                </h1>
            </div>
        </>
    );
}