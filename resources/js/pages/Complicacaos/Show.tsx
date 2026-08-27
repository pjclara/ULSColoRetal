import { Head } from '@inertiajs/react';

type Props = {
    complicacao: {
        id: number;
    };
};

export default function Show({ complicacao }: Props) {
    return (
        <>
            <Head title="Complicacao" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    Complicacao #{complicacao.id}
                </h1>
            </div>
        </>
    );
}