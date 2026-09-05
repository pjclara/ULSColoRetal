<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreListaDeEsperaRequest;
use App\Http\Requests\UpdateListaDeEsperaRequest;
use App\Models\ListaDeEspera;
use App\Services\UtenteService;
use App\Services\ListaDeEsperaService;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Concelho;
use App\Models\OrigemDaReferenciacao;
use App\Models\Diagnostico;
use App\Models\Destino;
use App\Models\EstadoDeAgendamento;
use App\Models\LocalDeAgendamento;
use App\Models\SalaDeAgendamento;
use App\Models\TipoDeAgendamento;

class ListaDeEsperaController extends Controller
{
    // service
    public function __construct(
        private ListaDeEsperaService $service,
        private UtenteService $utenteService
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->string('search')->trim()->toString();
        $listaDeEsperas = $this->service->paginate(15, $search, $request->string('estado')->trim()->toString());

        return inertia('ListaDeEsperas/Index', [
            'listaDeEsperas' => $listaDeEsperas,
            'filters' => [
                'search' => $search,
                'estado' => $request->string('estado')->trim()->toString(),
                'page' => $request->integer('page', 1),
            ],
            'estadoOptions' => [
                ['value' => '1', 'label' => 'Pendente'],
                ['value' => '2', 'label' => 'Em espera'],
                ['value' => '3', 'label' => 'Concluída'],
                ['value' => '4', 'label' => 'Cancelada'],
            ],
            'responsavelOptions' => User::query()
                ->whereActivo(true)
                ->orderBy('name')
                ->get(['id', 'name'])
                ->map(fn(User $user) => [
                    'value' => $user->id,
                    'label' => $user->name,
                ]),
            'diagnosticosOptions' => Diagnostico::query()
                ->orderBy('nome')
                ->get(['id', 'nome'])
                ->map(fn(Diagnostico $diagnostico) => [
                    'value' => $diagnostico->id,
                    'label' => $diagnostico->nome,
                ]),
            'destinosOptions' => Destino::query()
                ->orderBy('nome')
                ->get(['id', 'nome'])
                ->map(fn(Destino $destino) => [
                    'value' => $destino->id,
                    'label' => $destino->nome,
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
            'periodoDeAgendamentoOptions' => [
                ['value' => '1', 'label' => 'Manhã'],
                ['value' => '2', 'label' => 'Tarde'],
            ],
            'estadoDeAgendamentoOptions' => EstadoDeAgendamento::all()->map(fn(EstadoDeAgendamento $estado) => [
                'value' => $estado->id,
                'label' => $estado->nome,
            ]),

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $search = $request->input('search', '');
        $utenteId = $request->integer('utente_id');

        $utentes = $this->utenteService->paginate(15, $search);

        return inertia('ListaDeEsperas/Create', [
            'utentes' => $utentes,
            'filters' => [
                'search' => $search,
                'page' => $request->integer('page', 1),
            ],
            'utente' => $utenteId ? $this->utenteService->checkUtenteExists($utenteId) : null,
            'origems' => OrigemDaReferenciacao::pluck('nome', 'id'),
            'destinos' => Destino::pluck('nome', 'id'),
            'users' => User::query()->whereActivo(true)->orderBy('name')->get(['id', 'name']),
            'concelhoOptions' => Concelho::query()
                ->orderBy('nome')
                ->get(['id', 'nome'])
                ->map(fn(Concelho $concelho) => [
                    'value' => $concelho->id,
                    'label' => $concelho->nome,
                ]),
            'diagnosticosOptions' => Diagnostico::query()
                ->orderBy('nome')
                ->get(['id', 'nome'])
                ->map(fn(Diagnostico $diagnostico) => [
                    'value' => $diagnostico->id,
                    'label' => $diagnostico->nome,
                ]),
            'estadoOptions' => [
                ['value' => '1', 'label' => 'Pendente'],
                ['value' => '2', 'label' => 'Em espera'],
                ['value' => '3', 'label' => 'Concluída'],
                ['value' => '4', 'label' => 'Cancelada'],
            ],
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
            'periodoDeAgendamentoOptions' => [
                ['value' => '1', 'label' => 'Manhã'],
                ['value' => '2', 'label' => 'Tarde'],
            ],
            'estadoDeAgendamentoOptions' => EstadoDeAgendamento::all()->map(fn(EstadoDeAgendamento $estado) => [
                'value' => $estado->id,
                'label' => $estado->nome,
            ]),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreListaDeEsperaRequest $request)
    {
        $listaDeEspera = $this->service->create($request->validated());

        return redirect()
            ->route('lista-de-esperas.create', ['utente_id' => $listaDeEspera->utente_id])
            ->with('success', 'Lista de espera criada com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ListaDeEspera $listaDeEspera)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ListaDeEspera $listaDeEspera)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateListaDeEsperaRequest $request, ListaDeEspera $listaDeEspera)
    {
        $listaDeEspera = $this->service->update($listaDeEspera, $request->validated());

        return back()
            ->with('success', 'Lista de espera atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ListaDeEspera $listaDeEspera)
    {
        //
    }
}
