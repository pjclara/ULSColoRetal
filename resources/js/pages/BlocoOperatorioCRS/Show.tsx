import { Head } from '@inertiajs/react';

type Props = {
    blocoOperatorioCRS: {
        id: number;
    };
};

export default function Show({ blocoOperatorioCRS }: Props) {
    return (
        <>
            <Head title="BlocoOperatorioCRS" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    BlocoOperatorioCRS #{blocoOperatorioCRS.id}
                </h1>
            </div>
        </>
    );
}