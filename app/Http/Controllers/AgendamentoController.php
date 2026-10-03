<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAgendamentoRequest;
use App\Http\Requests\UpdateAgendamentoRequest;
use App\Models\Agendamento;
use App\Models\EstadoDeAgendamento;
use App\Models\ListaDeEspera;
use App\Models\LocalDeAgendamento;
use App\Models\SalaDeAgendamento;
use App\Models\TipoDeAgendamento;
use App\Models\User;
use App\Services\AgendamentoService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AgendamentoController extends Controller
{
    /** Estados de lista de espera que ainda podem receber um agendamento. */
    private const ESTADOS_LISTA_ESPERA_AGENDAVEIS = ['1', '2'];

    public function __construct(
        private AgendamentoService $service
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('Agendamentos/Index', [
            'agendamentos' => $this->service->paginate(15),
        ]);
    }

    /**
     * Calendário de gestão dos agendamentos (criar, editar e consultar por data).
     */
    public function calendar(Request $request)
    {
        $start = $request->query('start')
            ? Carbon::parse($request->query('start'))->startOfDay()
            : now()->startOfMonth()->subDays(7);

        $end = $request->query('end')
            ? Carbon::parse($request->query('end'))->endOfDay()
            : now()->endOfMonth()->addDays(7);

        $events = $this->service->betweenDates($start, $end)->map(function (Agendamento $agendamento) {
            $utente = $agendamento->listaDeEspera?->utente;

            return [
                'id' => $agendamento->id,
                'title' => $utente?->nome_curto ?? 'Sem utente',
                'start' => optional($agendamento->start)->toIso8601String(),
                'end' => optional($agendamento->end)->toIso8601String(),
                'extendedProps' => [
                    'lista_de_espera_id' => $agendamento->lista_de_espera_id,
                    'intervencaos' => $agendamento->listaDeEspera?->intervencoes,
                    'utente_nome' => $utente?->nome_curto ?? 'Utente desconhecido',
                    'numero_processo' => $utente?->numero_processo,
                    'responsavel_id' => $agendamento->responsavel_id,
                    'responsavel' => $agendamento->responsavel?->name,
                    'tipo_de_agendamento_id' => $agendamento->tipo_de_agendamento_id,
                    'tipo' => $agendamento->tipoDeAgendamento?->nome,
                    'local_de_agendamento_id' => $agendamento->local_de_agendamento_id,
                    'local' => $agendamento->localDeAgendamento?->nome,
                    'sala_de_agendamento_id' => $agendamento->sala_de_agendamento_id,
                    'sala' => $agendamento->salaDeAgendamento?->nome,
                    'periodo_de_agendamento_id' => $agendamento->periodo_de_agendamento_id,
                    'periodo' => $agendamento->periodoDeAgendamento?->nome,
                    'estado_de_agendamento_id' => $agendamento->estado_de_agendamento_id,
                    'estado' => $agendamento->estadoDeAgendamento?->nome,
                    'comentarios' => $agendamento->comentarios,
                ],
            ];
        });

        return inertia('Agendamentos/Calendar', [
            'events' => $events,
            'range' => [
                'start' => $start->toDateString(),
                'end' => $end->toDateString(),
            ],
            'listaDeEsperaOptions' => ListaDeEspera::query()
                ->with('utente')
                ->whereIn('estado_lista_espera', self::ESTADOS_LISTA_ESPERA_AGENDAVEIS)
                ->get()
                ->map(fn(ListaDeEspera $listaDeEspera) => [
                    'value' => $listaDeEspera->id,
                    'label' => trim(($listaDeEspera->utente?->nome ?? 'Utente desconhecido')
                        .($listaDeEspera->utente?->numero_processo ? ' — Proc. '.$listaDeEspera->utente->numero_processo : '')),
                ]),
            'responsavelOptions' => User::query()
                ->whereActivo(true)
                ->orderBy('name')
                ->get(['id', 'name'])
                ->map(fn(User $user) => [
                    'value' => $user->id,
                    'label' => $user->name,
                ]),
            'tipoDeAgendamentoOptions' => TipoDeAgendamento::all()->map(fn(TipoDeAgendamento $tipo) => [
                'value' => $tipo->id,
                'label' => $tipo->nome,
            ]),
            'localDeAgendamentoOptions' => LocalDeAgendamento::all()->map(fn(LocalDeAgendamento $local) => [
                'value' => $local->id,
                'label' => $local->nome,
            ]),
            'salaDeAgendamentoOptions' => SalaDeAgendamento::all()->map(fn(SalaDeAgendamento $sala) => [
                'value' => $sala->id,
                'label' => $sala->nome,
            ]),
            'estadoDeAgendamentoOptions' => EstadoDeAgendamento::optionsParaAgendamento(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAgendamentoRequest $request)
    {
        $agendamento = $this->service->create($request->validated());

        return back()
            ->with('agendamento', $agendamento)
            ->with('success', 'Agendamento criado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Agendamento $agendamento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Agendamento $agendamento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAgendamentoRequest $request, Agendamento $agendamento)
    {
        $agendamento = $this->service->update($agendamento, $request->validated());

        return back()
            ->with('agendamento', $agendamento)
            ->with('success', 'Agendamento atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Agendamento $agendamento)
    {
        $this->service->delete($agendamento);

        return back()->with('success', 'Agendamento removido com sucesso.');
    }
}
