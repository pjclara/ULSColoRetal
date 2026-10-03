import { AppPageHeader } from '@/components/app/app-page-header';
import AppLayout from '@/layouts/app-layout';
import { Head } from '@inertiajs/react';

const breadcrumbs = [
    {
        title: 'Estatísticas',
        href: route('estatisticas.index'),
    },
];

type Formato = 'int' | 'dec' | 'pct';

type Linha = {
    titulo: string;
    valores: Record<string, number | null>;
    formato: Formato;
};

type Tabela = {
    titulo: string;
    linhas: Linha[];
    nota?: string;
};

type Props = {
    anos: number[];
    tabelas: Tabela[];
};

const numero = new Intl.NumberFormat('pt-PT', { maximumFractionDigits: 2 });

function formatar(valor: number | null | undefined, formato: Formato) {
    if (valor === null || valor === undefined) {
        return '';
    }

    return formato === 'pct' ? `${numero.format(valor)}%` : numero.format(valor);
}

export default function Index({ anos, tabelas }: Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Estatísticas" />

            <div className="p-4">
                <AppPageHeader title="Estatísticas" description="Indicadores anuais calculados a partir dos internamentos e blocos operatórios." />

                <div className="space-y-8">
                    {tabelas.map((tabela) => (
                        <section key={tabela.titulo} aria-label={tabela.titulo} className="max-w-3xl">
                            <div className="overflow-x-auto">
                                <table className="w-full border-collapse text-sm">
                                    <caption className="sr-only">{tabela.titulo}</caption>
                                    <thead>
                                        <tr className="border-b-2 border-blue-300 dark:border-blue-700">
                                            <th scope="col" className="py-1.5 pr-4 text-left font-bold">
                                                {tabela.titulo}
                                            </th>
                                            {anos.map((ano) => (
                                                <th key={ano} scope="col" className="w-20 px-3 py-1.5 text-right font-bold tabular-nums">
                                                    {ano}
                                                </th>
                                            ))}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {tabela.linhas.map((linha) => (
                                            <tr key={linha.titulo} className="odd:bg-blue-50 dark:odd:bg-blue-950/40">
                                                <th scope="row" className="px-2 py-1.5 text-left font-semibold">
                                                    {linha.titulo}
                                                </th>
                                                {anos.map((ano) => (
                                                    <td key={ano} className="px-3 py-1.5 text-right tabular-nums">
                                                        {formatar(linha.valores[ano], linha.formato)}
                                                    </td>
                                                ))}
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>

                            {tabela.nota && <p className="mt-2 text-xs text-neutral-500 dark:text-neutral-400">{tabela.nota}</p>}
                        </section>
                    ))}
                </div>
            </div>
        </AppLayout>
    );
}
