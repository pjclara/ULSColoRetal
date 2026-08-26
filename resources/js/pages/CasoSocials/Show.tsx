import { Head } from '@inertiajs/react';

type Props = {
    casoSocial: {
        id: number;
    };
};

export default function Show({ casoSocial }: Props) {
    return (
        <>
            <Head title="CasoSocial" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    CasoSocial #{casoSocial.id}
                </h1>
            </div>
        </>
    );
}