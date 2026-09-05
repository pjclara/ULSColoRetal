import { Head } from '@inertiajs/react';

type Props = {
    blocoOperatorio: {
        id: number;
    };
};

export default function Show({ blocoOperatorio }: Props) {
    return (
        <>
            <Head title="BlocoOperatorio" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    BlocoOperatorio #{blocoOperatorio.id}
                </h1>
            </div>
        </>
    );
}