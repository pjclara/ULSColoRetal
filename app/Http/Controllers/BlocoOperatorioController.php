<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlocoOperatorioRequest;
use App\Http\Requests\UpdateBlocoOperatorioRequest;
use App\Models\BlocoOperatorio;
use App\Services\BlocoOperatorioService;

class BlocoOperatorioController extends Controller
{
    public function __construct(
        private BlocoOperatorioService $service
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Nota: sem página Inertia dedicada por agora; os blocos operatórios são
        // geridos inline a partir de Internamentos/Index.
        //
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
    public function store(StoreBlocoOperatorioRequest $request)
    {
        $blocoOperatorio = $this->service->create($request->validated());

        return back()
            ->with('blocoOperatorio', $blocoOperatorio)
            ->with('success', 'Bloco operatório adicionado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BlocoOperatorio $blocoOperatorio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BlocoOperatorio $blocoOperatorio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBlocoOperatorioRequest $request, BlocoOperatorio $blocoOperatorio)
    {
        $blocoOperatorio = $this->service->update($blocoOperatorio, $request->validated());

        return back()
            ->with('blocoOperatorio', $blocoOperatorio)
            ->with('success', 'Bloco operatório atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlocoOperatorio $blocoOperatorio)
    {
        $this->service->delete($blocoOperatorio);

        return back()->with('success', 'Bloco operatório removido com sucesso.');
    }
}
