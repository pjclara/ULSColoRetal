import { Head } from '@inertiajs/react';

type Props = {
    diagnostico: {
        id: number;
    };
};

export default function Show({ diagnostico }: Props) {
    return (
        <>
            <Head title="Diagnostico" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    Diagnostico #{diagnostico.id}
                </h1>
            </div>
        </>
    );
}