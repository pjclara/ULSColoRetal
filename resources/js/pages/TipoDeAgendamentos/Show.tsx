import { Head } from '@inertiajs/react';

type Props = {
    tipoDeAgendamento: {
        id: number;
    };
};

export default function Show({ tipoDeAgendamento }: Props) {
    return (
        <>
            <Head title="TipoDeAgendamento" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    TipoDeAgendamento #{tipoDeAgendamento.id}
                </h1>
            </div>
        </>
    );
}