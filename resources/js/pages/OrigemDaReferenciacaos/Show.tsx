import { Head } from '@inertiajs/react';

type Props = {
    origemDaReferenciacao: {
        id: number;
    };
};

export default function Show({ origemDaReferenciacao }: Props) {
    return (
        <>
            <Head title="OrigemDaReferenciacao" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    OrigemDaReferenciacao #{origemDaReferenciacao.id}
                </h1>
            </div>
        </>
    );
}