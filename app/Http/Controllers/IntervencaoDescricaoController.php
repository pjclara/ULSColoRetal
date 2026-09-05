<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIntervencaoDescricaoRequest;
use App\Http\Requests\UpdateIntervencaoDescricaoRequest;
use App\Models\IntervencaoDescricao;
use App\Services\IntervencaoDescricaoService;

class IntervencaoDescricaoController extends Controller
{
    public function __construct(
        private IntervencaoDescricaoService $service
    ) {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIntervencaoDescricaoRequest $request)
    {
        $data = $request->validated();
        $data['created_by_id'] = $request->user()?->id;
        $data['updated_by_id'] = $request->user()?->id;

        $intervencaoDescricao = $this->service->create($data);

        return back()
            ->with('intervencaoDescricao', $intervencaoDescricao)
            ->with('success', 'Descrição da intervenção guardada com sucesso.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIntervencaoDescricaoRequest $request, IntervencaoDescricao $intervencaoDescricao)
    {
        $data = $request->validated();
        $data['updated_by_id'] = $request->user()?->id;

        $intervencaoDescricao = $this->service->update($intervencaoDescricao, $data);

        return back()
            ->with('intervencaoDescricao', $intervencaoDescricao)
            ->with('success', 'Descrição da intervenção atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IntervencaoDescricao $intervencaoDescricao)
    {
        $this->service->delete($intervencaoDescricao);

        return back()->with('success', 'Descrição da intervenção removida com sucesso.');
    }
}
