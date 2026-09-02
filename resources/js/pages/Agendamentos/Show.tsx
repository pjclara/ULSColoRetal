import { Head } from '@inertiajs/react';

type Props = {
    agendamento: {
        id: number;
    };
};

export default function Show({ agendamento }: Props) {
    return (
        <>
            <Head title="Agendamento" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    Agendamento #{agendamento.id}
                </h1>
            </div>
        </>
    );
}