import { Head } from '@inertiajs/react';

type Props = {
    origemDaReferenciacao: {
        id: number;
    };
};

export default function Edit({ origemDaReferenciacao }: Props) {
    return (
        <>
            <Head title="Editar OrigemDaReferenciacao" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    Editar OrigemDaReferenciacao #{origemDaReferenciacao.id}
                </h1>
            </div>
        </>
    );
}