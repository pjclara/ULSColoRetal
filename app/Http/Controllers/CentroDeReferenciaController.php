<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCentroDeReferenciaRequest;
use App\Http\Requests\UpdateCentroDeReferenciaRequest;
use App\Models\CentroDeReferencia;
use Illuminate\Http\Request;

class CentroDeReferenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
    public function store(StoreCentroDeReferenciaRequest $request)
    {
        $validated = $request->validated();

        $centroDeReferencia = CentroDeReferencia::create($validated);

        return redirect()->back()->with('centro_de_referencia', $centroDeReferencia)->with('success', 'Centro de Referência criado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CentroDeReferencia $centroDeReferencia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CentroDeReferencia $centroDeReferencia)
    {
        dd($centroDeReferencia);
        return $centroDeReferencia;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCentroDeReferenciaRequest $request, CentroDeReferencia $centroDeReferencia)
    {
        $validated = $request->validated();

        $centroDeReferencia->update($validated);

        return redirect()->back()->with('centro_de_referencia', $centroDeReferencia)->with('success', 'Centro de Referência criado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CentroDeReferencia $centroDeReferencia)
    {
        //
    }
}
