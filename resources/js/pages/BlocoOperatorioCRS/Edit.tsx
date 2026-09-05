import { Head } from '@inertiajs/react';

type Props = {
    blocoOperatorioCRS: {
        id: number;
    };
};

export default function Edit({ blocoOperatorioCRS }: Props) {
    return (
        <>
            <Head title="Editar BlocoOperatorioCRS" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    Editar BlocoOperatorioCRS #{blocoOperatorioCRS.id}
                </h1>
            </div>
        </>
    );
}