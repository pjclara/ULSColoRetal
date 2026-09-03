import { Head } from '@inertiajs/react';

type Props = {
    salaDeAgendamento: {
        id: number;
    };
};

export default function Show({ salaDeAgendamento }: Props) {
    return (
        <>
            <Head title="SalaDeAgendamento" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    SalaDeAgendamento #{salaDeAgendamento.id}
                </h1>
            </div>
        </>
    );
}