<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAgendamentoRequest;
use App\Http\Requests\UpdateAgendamentoRequest;
use App\Models\Agendamento;
use App\Services\AgendamentoService;

class AgendamentoController extends Controller
{
    public function __construct(
        private AgendamentoService $service
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('Agendamentos/Index', [
            'agendamentos' => $this->service->paginate(15),
        ]);
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
    public function store(StoreAgendamentoRequest $request)
    {
        $agendamento = $this->service->create($request->validated());

        return back()
            ->with('agendamento', $agendamento)
            ->with('success', 'Agendamento criado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Agendamento $agendamento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Agendamento $agendamento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAgendamentoRequest $request, Agendamento $agendamento)
    {
        $agendamento = $this->service->update($agendamento, $request->validated());

        return back()
            ->with('agendamento', $agendamento)
            ->with('success', 'Agendamento atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Agendamento $agendamento)
    {
        $this->service->delete($agendamento);

        return back()->with('success', 'Agendamento removido com sucesso.');
    }
}
