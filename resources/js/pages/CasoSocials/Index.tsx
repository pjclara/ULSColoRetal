import { Head } from '@inertiajs/react';

type CasoSocialItem = {
    id: number;
    created_at?: string | null;
    updated_at?: string | null;
};

type Props = {
    casoSocials: {
        data: CasoSocialItem[];
    };
};

export default function Index({ casoSocials }: Props) {
    return (
        <>
            <Head title="CasoSocials" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    CasoSocials
                </h1>

                <div className="mt-6">
                    {casoSocials.data.length === 0 ? (
                        <p>Nenhum registo encontrado.</p>
                    ) : (
                        <ul>
                            {casoSocials.data.map((item) => (
                                <li key={item.id}>
                                    #{item.id}
                                </li>
                            ))}
                        </ul>
                    )}
                </div>
            </div>
        </>
    );
}