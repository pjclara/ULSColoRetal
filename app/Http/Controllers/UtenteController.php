<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUtenteRequest;
use App\Http\Requests\UpdateUtenteRequest;
use App\Models\Utente;
use Illuminate\Http\Request;

class UtenteController extends Controller
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
    public function store(StoreUtenteRequest $request)
    {
        $utente = Utente::create($request->validated());

        return back()
            ->with('utente', $utente)
            ->with('success', 'Utente criado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Utente $utente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Utente $utente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUtenteRequest $request, Utente $utente)
    {
        $utente->update($request->validated());

        return back()->with('success', 'Utente updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Utente $utente)
    {
        $utente->delete();
        return response()->json(null, 204);
    }
}
