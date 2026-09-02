import { Head } from '@inertiajs/react';

type Props = {
    agendamento: {
        id: number;
    };
};

export default function Edit({ agendamento }: Props) {
    return (
        <>
            <Head title="Editar Agendamento" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    Editar Agendamento #{agendamento.id}
                </h1>
            </div>
        </>
    );
}