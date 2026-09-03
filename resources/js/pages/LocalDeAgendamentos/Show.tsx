import { Head } from '@inertiajs/react';

type Props = {
    localDeAgendamento: {
        id: number;
    };
};

export default function Show({ localDeAgendamento }: Props) {
    return (
        <>
            <Head title="LocalDeAgendamento" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    LocalDeAgendamento #{localDeAgendamento.id}
                </h1>
            </div>
        </>
    );
}