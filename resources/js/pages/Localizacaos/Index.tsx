import { Head } from '@inertiajs/react';

type LocalizacaoItem = {
    id: number;
    created_at?: string | null;
    updated_at?: string | null;
};

type Props = {
    localizacaos: {
        data: LocalizacaoItem[];
    };
};

export default function Index({ localizacaos }: Props) {
    return (
        <>
            <Head title="Localizacaos" />

            <div className="p-6">
                <h1 className="text-2xl font-semibold">
                    Localizacaos
                </h1>

                <div className="mt-6">
                    {localizacaos.data.length === 0 ? (
                        <p>Nenhum registo encontrado.</p>
                    ) : (
                        <ul>
                            {localizacaos.data.map((item) => (
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