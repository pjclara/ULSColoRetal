import { Head } from '@inertiajs/react';

type Props = {
    internamento: {
        id: number;
    };
};

export default function Edit({ internamento }: Props) {
    return (
        <>
            <Head title="Editar Internamento" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    Editar Internamento #{internamento.id}
                </h1>
            </div>
        </>
    );
}