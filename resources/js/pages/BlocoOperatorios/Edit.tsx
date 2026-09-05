import { Head } from '@inertiajs/react';

type Props = {
    blocoOperatorio: {
        id: number;
    };
};

export default function Edit({ blocoOperatorio }: Props) {
    return (
        <>
            <Head title="Editar BlocoOperatorio" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    Editar BlocoOperatorio #{blocoOperatorio.id}
                </h1>
            </div>
        </>
    );
}