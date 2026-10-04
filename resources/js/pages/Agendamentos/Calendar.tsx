import { AppPageHeader } from '@/components/app/app-page-header';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { cn } from '@/lib/utils';
import { AgendamentoItem, Option } from '@/types/type';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin, { type DateClickArg } from '@fullcalendar/interaction';
import listPlugin from '@fullcalendar/list';
import ptLocale from '@fullcalendar/core/locales/pt';
import FullCalendar from '@fullcalendar/react';
import timeGridPlugin from '@fullcalendar/timegrid';
import type { DatesSetArg, EventClickArg, EventContentArg, EventDropArg } from '@fullcalendar/core';
import { Head, router } from '@inertiajs/react';
import { FileSpreadsheet, Plus } from 'lucide-react';
import { useMemo, useRef, useState } from 'react';
import toast from 'react-hot-toast';

import CreateOrUpdateAgendamento from './CreateOrUpdateAgendamento';

/** Grupo de local usado para separar o calendário em duas abas. */
type LocalGroup = 'bloco' | 'ambulatorio';

/** "Ambulatório" tem grupo próprio; qualquer outro local (Bloco central, Outro, ou por definir) fica no Bloco. */
function groupForLocal(local: string | null): LocalGroup {
    return (local ?? '').trim().toLowerCase() === 'ambulatorio' ? 'ambulatorio' : 'bloco';
}

type CalendarEventProps = {
    lista_de_espera_id: number | null;
    utente_nome: string | null;
    numero_processo: string | null;
    responsavel_id: number | null;
    responsavel: string | null;
    tipo_de_agendamento_id: number | null;
    tipo: string | null;
    local_de_agendamento_id: number | null;
    local: string | null;
    sala_de_agendamento_id: number | null;
    sala: string | null;
    periodo_de_agendamento_id: number | null;
    periodo: string | null;
    estado_de_agendamento_id: number | null;
    estado: string | null;
    comentarios: string | null;
    intervencaos: any[];
};

type CalendarEvent = {
    id: number;
    title: string;
    start: string | null;
    end: string | null;
    extendedProps: CalendarEventProps;
};

type Props = {
    events: CalendarEvent[];
    range: { start: string; end: string };
    listaDeEsperaOptions: Option[];
    responsavelOptions: Option[];
    tipoDeAgendamentoOptions: Option[];
    localDeAgendamentoOptions: Option[];
    salaDeAgendamentoOptions: Option[];
    estadoDeAgendamentoOptions: Option[];
};

/** Cor por nome de estado (não pelo id, que não é garantido entre ambientes — ver AgendamentoService). */
const ESTADO_COLORS: Record<string, { bg: string; border: string; text: string }> = {
    proposto: { bg: '#e2e8f0', border: '#94a3b8', text: '#334155' },
    pendente: { bg: '#fef3c7', border: '#f59e0b', text: '#92400e' },
    agendado: { bg: '#dbeafe', border: '#3b82f6', text: '#1e40af' },
    cancelado: { bg: '#fee2e2', border: '#ef4444', text: '#991b1b' },
    operado: { bg: '#dcfce7', border: '#22c55e', text: '#166534' },
};
const DEFAULT_ESTADO_COLOR = { bg: '#f1f5f9', border: '#94a3b8', text: '#334155' };

function colorFor(estado: string | null) {
    if (!estado) {
        return DEFAULT_ESTADO_COLOR;
    }
    const color = ESTADO_COLORS[estado.trim().toLowerCase()] ?? DEFAULT_ESTADO_COLOR;
    return color;
}

/** Converte uma data (ISO ou objeto Date) para o formato esperado pelo input datetime-local, em hora local. */
function toLocalInputValue(value: string | Date | null): string {
    if (!value) {
        return '';
    }

    const date = typeof value === 'string' ? new Date(value) : value;

    if (Number.isNaN(date.getTime())) {
        return '';
    }

    const pad = (n: number) => String(n).padStart(2, '0');

    return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
}

const breadcrumbs = [
    {
        title: 'Agendamentos',
        href: route('agendamentos.index'),
    },
    {
        title: 'Calendário',
        href: route('agendamentos.calendar'),
    },
];

export default function Calendar({
    events,
    listaDeEsperaOptions,
    responsavelOptions,
    tipoDeAgendamentoOptions,
    localDeAgendamentoOptions,
    salaDeAgendamentoOptions,
    estadoDeAgendamentoOptions,
}: Props) {
    const calendarRef = useRef<FullCalendar>(null);

    const [isOpen, setIsOpen] = useState(false);
    const [selected, setSelected] = useState<Partial<AgendamentoItem> | null>(null);
    const [deleting, setDeleting] = useState(false);
    const [tab, setTab] = useState<LocalGroup>('bloco');

    const { blocoEvents, ambulatorioEvents } = useMemo(() => {
        const bloco: CalendarEvent[] = [];
        const ambulatorio: CalendarEvent[] = [];

        for (const event of events) {
            (groupForLocal(event.extendedProps.local) === 'ambulatorio' ? ambulatorio : bloco).push(event);
        }

        return { blocoEvents: bloco, ambulatorioEvents: ambulatorio };
    }, [events]);

    const visibleEvents = tab === 'ambulatorio' ? ambulatorioEvents : blocoEvents;

    /** Id do local de agendamento correspondente à aba ativa, para pré-preencher novos agendamentos. */
    const defaultLocalIdForTab = useMemo(() => {
        const label = tab === 'ambulatorio' ? 'ambulatorio' : 'bloco central';
        const option = localDeAgendamentoOptions.find((o) => o.label.trim().toLowerCase() === label);

        return option ? Number(option.value) : undefined;
    }, [tab, localDeAgendamentoOptions]);

    const openCreate = (start: Date) => {
        const value = toLocalInputValue(start);

        setSelected({
            start: value,
            end: value,
            local_de_agendamento_id: defaultLocalIdForTab,
        });
        setIsOpen(true);
    };

    const openEdit = (event: CalendarEvent) => {
        const props = event.extendedProps;
        const start = toLocalInputValue(event.start);

        setSelected({
            id: event.id,
            lista_de_espera_id: props.lista_de_espera_id ?? 0,
            start,
            end: start,
            responsavel_id: props.responsavel_id,
            tipo_de_agendamento_id: props.tipo_de_agendamento_id ?? 0,
            local_de_agendamento_id: props.local_de_agendamento_id ?? 0,
            sala_de_agendamento_id: props.sala_de_agendamento_id ?? 0,
            periodo_de_agendamento_id: props.periodo_de_agendamento_id ?? 0,
            estado_de_agendamento_id: props.estado_de_agendamento_id ?? 0,
            comentarios: props.comentarios,
        });
        setIsOpen(true);
    };

    const closeModal = () => {
        setIsOpen(false);
        setSelected(null);
    };

    /**
     * A vista de mês/semana/dia mudou (navegação ou troca de vista): pede ao servidor apenas
     * os agendamentos da janela agora visível, sem navegar a página inteira.
     */
    const handleDatesSet = (arg: DatesSetArg) => {
        router.get(
            route('agendamentos.calendar'),
            {
                start: arg.startStr.slice(0, 10),
                end: arg.endStr.slice(0, 10),
            },
            {
                only: ['events', 'range'],
                preserveState: true,
                preserveScroll: true,
                replace: true,
            },
        );
    };

    const handleDateClick = (arg: DateClickArg) => {
        const start = new Date(arg.date);

        if (arg.allDay) {
            start.setHours(9, 0, 0, 0);
        }

        openCreate(start);
    };

    const importInputRef = useRef<HTMLInputElement>(null);

    /** Envia o Excel (DTA_INTERVENCAO, NUM_PROCESSO) para marcar os agendamentos correspondentes como "Operado". */
    const handleImportOperados = (event: React.ChangeEvent<HTMLInputElement>) => {
        const ficheiro = event.target.files?.[0];
        event.target.value = '';

        if (!ficheiro) {
            return;
        }

        router.post(
            route('agendamentos.importar-operados'),
            { ficheiro },
            {
                forceFormData: true,
                preserveScroll: true,
                onSuccess: (page) => toast.success((page.props as any).flash?.success ?? 'Importação concluída.'),
                onError: (errors) => toast.error(errors.ficheiro ?? 'Não foi possível importar o ficheiro.'),
            },
        );
    };

    /** Abre a criação com a próxima hora "redonda" pré-preenchida (usado pelo botão "Novo agendamento"). */
    const openCreateNow = () => {
        const start = new Date();
        start.setMinutes(0, 0, 0);
        start.setHours(start.getHours() + 1);

        openCreate(start);
    };

    const handleEventClick = (arg: EventClickArg) => {
        openEdit({
            id: Number(arg.event.id),
            title: arg.event.title,
            start: arg.event.start ? arg.event.start.toISOString() : null,
            end: arg.event.end ? arg.event.end.toISOString() : null,
            extendedProps: arg.event.extendedProps as CalendarEventProps,
        });
    };

    const handleDelete = () => {
        if (!selected?.id) {
            return;
        }

        if (!window.confirm('Remover este agendamento?')) {
            return;
        }

        setDeleting(true);

        router.delete(route('agendamentos.destroy', selected.id), {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Agendamento removido com sucesso.');
                closeModal();
            },
            onError: () => toast.error('Não foi possível remover o agendamento.'),
            onFinish: () => setDeleting(false),
        });
    };

    /**
     * Arrastar um evento reagenda-o. Um agendamento é um ponto no tempo — o "fim" acompanha
     * sempre o início (ver AgendamentoService) — por isso não se envia um fim distinto, e
     * `event.end` nunca bloqueia o pedido (mesmo que o FullCalendar o devolva como null).
     */
    const handleEventDrop = (arg: EventDropArg) => {
        const { event, revert } = arg;

        if (!event.start) {
            revert();
            return;
        }

        const props = event.extendedProps as CalendarEventProps;

        router.put(
            route('agendamentos.update', event.id),
            {
                lista_de_espera_id: props.lista_de_espera_id,
                start: event.start.toISOString(),
                responsavel_id: props.responsavel_id,
                tipo_de_agendamento_id: props.tipo_de_agendamento_id,
                local_de_agendamento_id: props.local_de_agendamento_id,
                sala_de_agendamento_id: props.sala_de_agendamento_id,
                periodo_de_agendamento_id: props.periodo_de_agendamento_id,
                estado_de_agendamento_id: props.estado_de_agendamento_id,
                comentarios: props.comentarios,
            },
            {
                preserveScroll: true,
                onSuccess: () => toast.success('Agendamento reagendado com sucesso.'),
                onError: (errors) => {
                    console.error('Falha ao mover o agendamento:', errors);
                    toast.error('Não foi possível mover o agendamento.');
                    revert();
                },
            },
        );
    };

    const renderEventContent = (arg: EventContentArg) => {
        const props = arg.event.extendedProps as CalendarEventProps;
        const intervencaos = props.intervencaos ?? [];
        const details = [props.tipo, props.sala ? `Sala ${props.sala}` : null].filter(Boolean).join(' · ');
        

        return (
            <div className="overflow-hidden px-1 py-0.5 text-xs leading-tight">
                <div className="truncate font-medium">{arg.timeText ? `${arg.timeText} ${arg.event.title}` : arg.event.title}</div>
                {details && <div className="truncate opacity-80">{details}</div>}
                {intervencaos.map((intervencao) => (
                    <div key={intervencao.id} className="truncate opacity-80">
                        {intervencao.nome}
                    </div>
                ))}
            </div>
        );
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Calendário de Agendamentos" />

            <div className="p-6">
                <AppPageHeader
                    title="Calendário de Agendamentos"
                    description="Consulte, crie e edite agendamentos por data, sala e período."
                    action={
                        <div className="flex gap-2">
                            <input
                                ref={importInputRef}
                                type="file"
                                accept=".xlsx,.xls,.csv"
                                className="hidden"
                                onChange={handleImportOperados}
                            />
                            <Button type="button" size="sm" variant="outline" onClick={() => importInputRef.current?.click()}>
                                <FileSpreadsheet className="size-4" />
                                Importar operados
                            </Button>
                            <Button type="button" size="sm" onClick={openCreateNow}>
                                <Plus className="size-4" />
                                Novo agendamento
                            </Button>
                        </div>
                    }
                >
                    <div className="flex flex-wrap gap-3 text-xs text-neutral-500 dark:text-neutral-400">
                        {estadoDeAgendamentoOptions.map((option) => {
                            const color = colorFor(option.label);

                            return (
                                <span key={option.value} className="inline-flex items-center gap-1.5">
                                    <span className="size-2.5 rounded-full" style={{ backgroundColor: color.border }} />
                                    {option.label}
                                </span>
                            );
                        })}
                    </div>
                </AppPageHeader>

                <div className="mb-4 inline-flex rounded-lg border border-neutral-200 p-1 dark:border-neutral-800">
                    <button
                        type="button"
                        onClick={() => setTab('bloco')}
                        className={cn(
                            'rounded-md px-4 py-1.5 text-sm font-medium transition-colors',
                            tab === 'bloco'
                                ? 'bg-neutral-900 text-white dark:bg-white dark:text-neutral-900'
                                : 'text-neutral-600 hover:bg-neutral-100 dark:text-neutral-300 dark:hover:bg-neutral-800',
                        )}
                    >
                        Bloco Operatório
                        <span className="ml-1.5 opacity-60">{blocoEvents.length}</span>
                    </button>

                    <button
                        type="button"
                        onClick={() => setTab('ambulatorio')}
                        className={cn(
                            'rounded-md px-4 py-1.5 text-sm font-medium transition-colors',
                            tab === 'ambulatorio'
                                ? 'bg-neutral-900 text-white dark:bg-white dark:text-neutral-900'
                                : 'text-neutral-600 hover:bg-neutral-100 dark:text-neutral-300 dark:hover:bg-neutral-800',
                        )}
                    >
                        Ambulatório
                        <span className="ml-1.5 opacity-60">{ambulatorioEvents.length}</span>
                    </button>
                </div>

                <div className="app-calendar rounded-xl border border-neutral-200 bg-white p-3 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                    <FullCalendar
                        key={tab}
                        ref={calendarRef}
                        plugins={[dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin]}
                        initialView="dayGridMonth"
                        locale={ptLocale}
                        headerToolbar={{
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek',
                        }}
                        height="auto"
                        eventDisplay="block"
                        editable
                        eventStartEditable
                        eventDurationEditable={false}
                        events={visibleEvents.map((event) => {
                            const color = colorFor(event.extendedProps.estado);

                            return {
                                ...event,
                                id: String(event.id),
                                start: event.start ?? undefined,
                                end: event.end ?? undefined,
                                backgroundColor: color.bg,
                                borderColor: color.border,
                                textColor: color.text,
                            };
                        })}
                        eventContent={renderEventContent}
                        dateClick={handleDateClick}
                        eventClick={handleEventClick}
                        eventDrop={handleEventDrop}
                        datesSet={handleDatesSet}
                        firstDay={1}
                        nowIndicator
                        navLinks
                        dayMaxEvents
                        weekends
                    />
                </div>
            </div>

            <CreateOrUpdateAgendamento
                agendamento={selected}
                isOpenAgendamento={isOpen}
                onClose={closeModal}
                onSuccess={closeModal}
                onDelete={handleDelete}
                deleting={deleting}
                listaDeEsperaOptions={listaDeEsperaOptions}
                responsavelOptions={responsavelOptions}
                tipoDeAgendamentoOptions={tipoDeAgendamentoOptions}
                localDeAgendamentoOptions={localDeAgendamentoOptions}
                salaDeAgendamentoOptions={salaDeAgendamentoOptions}
                estadoDeAgendamentoOptions={estadoDeAgendamentoOptions}
            />
        </AppLayout>
    );
}
