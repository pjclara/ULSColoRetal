import { Head } from '@inertiajs/react';

type Props = {
    origemDoInternamento: {
        id: number;
    };
};

export default function Show({ origemDoInternamento }: Props) {
    return (
        <>
            <Head title="OrigemDoInternamento" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    OrigemDoInternamento #{origemDoInternamento.id}
                </h1>
            </div>
        </>
    );
}