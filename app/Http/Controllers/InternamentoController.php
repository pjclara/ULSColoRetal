<?php

namespace App\Http\Controllers;

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

        return inertia('Internamentos/Create', [
            'utentes' => $utentes,
            'filters' => [
                'search' => $request->input('search', ''),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, Internamento $internamento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Internamento $internamento)
    {
        //
    }
}
