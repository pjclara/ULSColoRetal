import { Head } from '@inertiajs/react';

type Props = {
    origemDoInternamento: {
        id: number;
    };
};

export default function Edit({ origemDoInternamento }: Props) {
    return (
        <>
            <Head title="Editar OrigemDoInternamento" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    Editar OrigemDoInternamento #{origemDoInternamento.id}
                </h1>
            </div>
        </>
    );
}