import { Head } from '@inertiajs/react';

type Props = {
    estadoDaAlta: {
        id: number;
    };
};

export default function Show({ estadoDaAlta }: Props) {
    return (
        <>
            <Head title="EstadoDaAlta" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    EstadoDaAlta #{estadoDaAlta.id}
                </h1>
            </div>
        </>
    );
}