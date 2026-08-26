import { Head } from '@inertiajs/react';

type Props = {
    casoSocial: {
        id: number;
    };
};

export default function Edit({ casoSocial }: Props) {
    return (
        <>
            <Head title="Editar CasoSocial" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    Editar CasoSocial #{casoSocial.id}
                </h1>
            </div>
        </>
    );
}