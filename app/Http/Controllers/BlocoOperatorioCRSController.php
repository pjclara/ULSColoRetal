<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlocoOperatorioCRSRequest;
use App\Http\Requests\UpdateBlocoOperatorioCRSRequest;
use App\Models\BlocoOperatorioCRS;
use App\Services\BlocoOperatorioCRSService;

class BlocoOperatorioCRSController extends Controller
{
    public function __construct(
        private BlocoOperatorioCRSService $service
    ) {}

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
    public function store(StoreBlocoOperatorioCRSRequest $request)
    {
        $data = $request->validated();
        $data['created_by_id'] = $request->user()?->id;

        $blocoOperatorioCRS = $this->service->create($data);

        return back()
            ->with('blocoOperatorioCRS', $blocoOperatorioCRS)
            ->with('success', 'Dados de centro de referência guardados com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BlocoOperatorioCRS $blocoOperatorioCRS)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BlocoOperatorioCRS $blocoOperatorioCRS)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBlocoOperatorioCRSRequest $request, BlocoOperatorioCRS $blocoOperatorioCRS)
    {
        $data = $request->validated();
        $data['updated_by_id'] = $request->user()?->id;

        $blocoOperatorioCRS = $this->service->update($blocoOperatorioCRS, $data);

        return back()
            ->with('blocoOperatorioCRS', $blocoOperatorioCRS)
            ->with('success', 'Dados de centro de referência atualizados com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlocoOperatorioCRS $blocoOperatorioCRS)
    {
        $this->service->delete($blocoOperatorioCRS);

        return back()->with('success', 'Dados de centro de referência removidos com sucesso.');
    }
}
