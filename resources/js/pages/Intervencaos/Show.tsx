import { Head } from '@inertiajs/react';

type Props = {
    intervencao: {
        id: number;
    };
};

export default function Show({ intervencao }: Props) {
    return (
        <>
            <Head title="Intervencao" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    Intervencao #{intervencao.id}
                </h1>
            </div>
        </>
    );
}