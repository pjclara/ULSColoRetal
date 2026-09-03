<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreListaDeEsperaRequest;
use App\Models\ListaDeEspera;
use App\Services\UtenteService;
use App\Services\ListaDeEsperaService;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OrigemDaReferenciacao;
use App\Models\Destino;

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
    public function index()
    {
        $listaDeEsperas = $this->service->paginate();

        return inertia('ListaDeEsperas/Index', [

            'listaDeEsperas' => $listaDeEsperas,
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
            'estadoOptions' => [
                ['value' => '1', 'label' => 'Pendente'],
                ['value' => '2', 'label' => 'Em espera'],
                ['value' => '3', 'label' => 'Concluída'],
                ['value' => '4', 'label' => 'Cancelada'],
            ],

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
    public function update(Request $request, ListaDeEspera $listaDeEspera)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ListaDeEspera $listaDeEspera)
    {
        //
    }
}
