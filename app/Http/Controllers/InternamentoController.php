<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInternamentoRequest;
use App\Http\Requests\UpdateInternamentoRequest;
use App\Models\Internamento;
use App\Models\Utente;
use App\Services\InternamentoService;
use App\Services\UtenteService;
use Illuminate\Http\Request;

class InternamentoController extends Controller
{
    public function __construct(
        private InternamentoService $service,
        private UtenteService $utenteService
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $internamentos = $this->service->paginate(10);


        return inertia('Internamentos/Index', [
            'internamentos' => $internamentos,
            'internamentoOptions' => [
                'origensInternamento' => $this->service->getOrigensInternamento(),
                'estadosAlta' => $this->service->getEstadosAlta(),
                'responsaveis' => $this->service->getResponsaveis(),
                'clavienDindo' => $this->service->getClavienDindo(),
                'destinos' => $this->service->getDestinos(),
                'casosSociais' => $this->service->getCasosSociais(),
                'localizacoes' => $this->service->getLocalizacoes(),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $utentes = $this->utenteService->search(
            $request->only(['search'])
        );

        $internamentos = $this->service
            ->forUtente(
                $request->integer('utente_id'),
                $request->input('search')
            );

        return inertia('Internamentos/Create', [
            'utentes' => $utentes,
            'internamentos' => $internamentos,
            'filters' => [
                'search' => $request->input('search', ''),
            ],
            'internamentoOptions' => [
                'origensInternamento' => $this->service->getOrigensInternamento(),
                'estadosAlta' => $this->service->getEstadosAlta(),
                'responsaveis' => $this->service->getResponsaveis(),
                'clavienDindo' => $this->service->getClavienDindo(),
                'destinos' => $this->service->getDestinos(),
                'casosSociais' => $this->service->getCasosSociais(),
                'localizacoes' => $this->service->getLocalizacoes(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInternamentoRequest $request)
    {
        $internamento = $this->service->create($request->validated());

        return redirect()
            ->route('internamentos.index')
            ->with('success', 'Internamento criado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Internamento $internamento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Internamento $internamento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInternamentoRequest $request, Internamento $internamento)
    {
        $internamento = $this->service->update($internamento, $request->validated());

        return redirect()
            ->route('internamentos.index')
            ->with('success', 'Internamento atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Internamento $internamento)
    {
        //
    }
}
