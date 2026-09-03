import { Head } from '@inertiajs/react';

type Props = {
    localDeAgendamento: {
        id: number;
    };
};

export default function Edit({ localDeAgendamento }: Props) {
    return (
        <>
            <Head title="Editar LocalDeAgendamento" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    Editar LocalDeAgendamento #{localDeAgendamento.id}
                </h1>
            </div>
        </>
    );
}