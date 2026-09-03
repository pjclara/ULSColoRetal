import { Head } from '@inertiajs/react';

type Props = {
    salaDeAgendamento: {
        id: number;
    };
};

export default function Edit({ salaDeAgendamento }: Props) {
    return (
        <>
            <Head title="Editar SalaDeAgendamento" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    Editar SalaDeAgendamento #{salaDeAgendamento.id}
                </h1>
            </div>
        </>
    );
}