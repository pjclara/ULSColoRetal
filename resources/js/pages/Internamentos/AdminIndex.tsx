import { AppFilters } from '@/components/app/app-filters';
import { AppPageHeader } from '@/components/app/app-page-header';
import { AppPagination } from '@/components/app/app-pagination';
import AppLayout from '@/layouts/app-layout';
import type { InternamentoOptions, LookupOption, ResolucaoComplicacaoOption } from '@/types/internamento';
import { InternamentoItem, Pagination } from '@/types/type';
import { Head, router } from '@inertiajs/react';
import { Pencil, Search } from 'lucide-react';
import { useState, type ReactNode } from 'react';
import CreateOrUpdateInternamentoModal from './CreateOrUpdateInternamentoModal';

const breadcrumbs = [{ title: 'Todos os internamentos', href: route('admin.internamentos.index') }];

type Filters = {
    search: string;
    estado: string;
    localizacao_id: string | number;
    responsavel_id: string | number;
    destino_id: string | number;
    operado: string;
    entrada_de: string;
    entrada_ate: string;
};

type Props = {
    internamentos: Pagination<InternamentoItem>;
    filters: Filters;
    complicacoesOptions: LookupOption[];
    resolucoesComplicacaoOptions: ResolucaoComplicacaoOption[];
    internamentoOptions: InternamentoOptions;
};

const EMPTY_FILTERS: Filters = {
    search: '',
    estado: '',
    localizacao_id: '',
    responsavel_id: '',
    destino_id: '',
    operado: '',
    entrada_de: '',
    entrada_ate: '',
};

const inputClass =
    'border-input bg-background focus-visible:ring-ring w-full rounded-md border px-3 py-2 text-sm focus-visible:ring-2 focus-visible:outline-none';

function formatData(data?: string | null) {
    return data ? new Intl.DateTimeFormat('pt-PT').format(new Date(data)) : '-';
}

function Field({ label, children }: { label: string; children: ReactNode }) {
    return (
        <label className="flex flex-col gap-1 text-xs font-medium text-neutral-500 dark:text-neutral-400">
            {label}
            {children}
        </label>
    );
}

export default function AdminIndex({ internamentos, filters, complicacoesOptions, resolucoesComplicacaoOptions, internamentoOptions }: Props) {
    const [values, setValues] = useState<Filters>({ ...EMPTY_FILTERS, ...filters });
    const [selected, setSelected] = useState<InternamentoItem | null>(null);

    const set = (key: keyof Filters, value: string) => setValues((current) => ({ ...current, [key]: value }));

    const apply = (next: Filters) => {
        const params = Object.fromEntries(Object.entries(next).filter(([, value]) => value !== '' && value != null));
        router.get(route('admin.internamentos.index'), params, { preserveState: true, preserveScroll: true, replace: true });
    };

    const reset = () => {
        setValues(EMPTY_FILTERS);
        apply(EMPTY_FILTERS);
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Todos os internamentos" />

            <div className="p-6">
                <AppPageHeader
                    title="Todos os internamentos"
                    description="Listagem completa (ativos e com saída) com edição de dados. Visível apenas a superAdmin."
                />

                <AppFilters
                    onSubmit={(event) => {
                        event.preventDefault();
                        apply(values);
                    }}
                    onReset={reset}
                >
                    <Field label="Pesquisa">
                        <div className="relative">
                            <Search className="pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2 text-neutral-400" />
                            <input
                                type="text"
                                value={values.search}
                                onChange={(e) => set('search', e.target.value)}
                                placeholder="Nome, processo, cama ou motivo"
                                className={`${inputClass} pl-9`}
                            />
                        </div>
                    </Field>

                    <Field label="Estado">
                        <select value={values.estado} onChange={(e) => set('estado', e.target.value)} className={inputClass}>
                            <option value="">Todos</option>
                            <option value="ativos">Ativos (sem saída)</option>
                            <option value="saidos">Com saída</option>
                        </select>
                    </Field>

                    <Field label="Localização">
                        <select value={values.localizacao_id} onChange={(e) => set('localizacao_id', e.target.value)} className={inputClass}>
                            <option value="">Todas</option>
                            {internamentoOptions.localizacoes.map((o) => (
                                <option key={o.id} value={o.id}>
                                    {o.nome}
                                </option>
                            ))}
                        </select>
                    </Field>

                    <Field label="Responsável">
                        <select value={values.responsavel_id} onChange={(e) => set('responsavel_id', e.target.value)} className={inputClass}>
                            <option value="">Todos</option>
                            {internamentoOptions.responsaveis.map((o) => (
                                <option key={o.id} value={o.id}>
                                    {o.name}
                                </option>
                            ))}
                        </select>
                    </Field>

                    <Field label="Destino">
                        <select value={values.destino_id} onChange={(e) => set('destino_id', e.target.value)} className={inputClass}>
                            <option value="">Todos</option>
                            {internamentoOptions.destinos.map((o) => (
                                <option key={o.id} value={o.id}>
                                    {o.nome}
                                </option>
                            ))}
                        </select>
                    </Field>

                    <Field label="Operado">
                        <select value={values.operado} onChange={(e) => set('operado', e.target.value)} className={inputClass}>
                            <option value="">Todos</option>
                            <option value="sim">Com bloco operatório</option>
                            <option value="nao">Sem bloco operatório</option>
                        </select>
                    </Field>

                    <Field label="Entrada de">
                        <input type="date" value={values.entrada_de} onChange={(e) => set('entrada_de', e.target.value)} className={inputClass} />
                    </Field>

                    <Field label="Entrada até">
                        <input type="date" value={values.entrada_ate} onChange={(e) => set('entrada_ate', e.target.value)} className={inputClass} />
                    </Field>
                </AppFilters>

                {internamentos.data.length === 0 ? (
                    <p className="text-muted-foreground">Nenhum internamento encontrado.</p>
                ) : (
                    <div className="overflow-hidden rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                        <div className="overflow-x-auto">
                            <table className="w-full text-sm">
                                <thead className="border-b border-neutral-200 bg-neutral-50 dark:border-neutral-800 dark:bg-neutral-950">
                                    <tr>
                                        {['Processo', 'Nome', 'Entrada', 'Saída', 'Dias', 'Localização', 'Cama', 'Responsável', 'Motivo', 'Bloco(s)', ''].map(
                                            (label, index) => (
                                                <th
                                                    key={index}
                                                    scope="col"
                                                    className="px-4 py-3 text-left text-xs font-semibold tracking-wide text-neutral-500 uppercase dark:text-neutral-400"
                                                >
                                                    {label}
                                                </th>
                                            ),
                                        )}
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-neutral-200 dark:divide-neutral-800">
                                    {internamentos.data.map((item) => (
                                        <tr key={item.id} className="transition-colors hover:bg-neutral-50 dark:hover:bg-neutral-800/40">
                                            <td className="px-4 py-3 align-top">{item.numero_processo}</td>
                                            <td className="px-4 py-3 align-top font-medium">{item.nome_curto}</td>
                                            <td className="px-4 py-3 align-top whitespace-nowrap">{formatData(item.data_de_entrada)}</td>
                                            <td className="px-4 py-3 align-top whitespace-nowrap">{formatData(item.data_de_saida)}</td>
                                            <td className="px-4 py-3 align-top">{item.dias_internamento}</td>
                                            <td className="px-4 py-3 align-top">{item.localizacao}</td>
                                            <td className="px-4 py-3 align-top">{item.cama}</td>
                                            <td className="px-4 py-3 align-top">{item.responsavel}</td>
                                            <td className="px-4 py-3 align-top">{item.motivo_internamento}</td>
                                            <td className="px-4 py-3 align-top">
                                                {item.bloco_operatorios?.map((bloco) => (
                                                    <div key={bloco.id} className="text-xs whitespace-nowrap text-neutral-600 dark:text-neutral-400">
                                                        {formatData(bloco.data_de_inicio)}
                                                    </div>
                                                ))}
                                            </td>
                                            <td className="px-4 py-3 align-top">
                                                <button
                                                    type="button"
                                                    onClick={() => setSelected(item)}
                                                    className="inline-flex items-center gap-1 text-xs font-medium text-neutral-600 hover:underline dark:text-neutral-300"
                                                >
                                                    <Pencil className="size-3.5" />
                                                    Editar
                                                </button>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>
                )}

                <AppPagination
                    links={internamentos.links}
                    from={internamentos.from ?? undefined}
                    to={internamentos.to ?? undefined}
                    total={internamentos.total ?? undefined}
                />

                {selected && (
                    <CreateOrUpdateInternamentoModal
                        open
                        onClose={() => setSelected(null)}
                        internamento={selected}
                        utenteId={selected.utente_id}
                        internamentoOptions={internamentoOptions}
                        complicacoesOptions={complicacoesOptions}
                        resolucoesComplicacaoOptions={resolucoesComplicacaoOptions}
                    />
                )}
            </div>
        </AppLayout>
    );
}
