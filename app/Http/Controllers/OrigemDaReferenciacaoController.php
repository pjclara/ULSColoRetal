<?php

namespace App\Http\Controllers;

use App\Models\OrigemDaReferenciacao;
use Illuminate\Http\Request;

class OrigemDaReferenciacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $origens = OrigemDaReferenciacao::all();

        return $origens;
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(OrigemDaReferenciacao $origemDaReferenciacao)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrigemDaReferenciacao $origemDaReferenciacao)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrigemDaReferenciacao $origemDaReferenciacao)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrigemDaReferenciacao $origemDaReferenciacao)
    {
        //
    }
}
