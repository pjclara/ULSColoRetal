import { Head } from '@inertiajs/react';

type Props = {
    intervencao: {
        id: number;
    };
};

export default function Edit({ intervencao }: Props) {
    return (
        <>
            <Head title="Editar Intervencao" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    Editar Intervencao #{intervencao.id}
                </h1>
            </div>
        </>
    );
}