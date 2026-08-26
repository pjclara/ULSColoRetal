import { Head } from '@inertiajs/react';

type Props = {
    internamento: {
        id: number;
    };
};

export default function Show({ internamento }: Props) {
    return (
        <>
            <Head title="Internamento" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    Internamento #{internamento.id}
                </h1>
            </div>
        </>
    );
}