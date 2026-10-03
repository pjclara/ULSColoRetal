<?php

namespace App\Http\Controllers;

use App\Models\ClavienDindo;
use App\Models\ResolucaoComplicacao;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ResolucaoComplicacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $resolucoesComplicacao = ResolucaoComplicacao::query()
            ->with('clavienDindo')
            ->orderBy('nome')
            ->paginate(15);

        return Inertia::render('ResolucoesComplicacao/Index', [
            'resolucoesComplicacao' => $resolucoesComplicacao,
            'clavienDindoOptions' => ClavienDindo::query()->orderBy('id')->get(['id', 'nome']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => ['required', 'string', 'max:191', 'unique:resolucao_complicacaos,nome'],
            'clavien_dindo_id' => ['nullable', 'integer', 'exists:clavien_dindos,id'],
        ]);

        ResolucaoComplicacao::create($data);

        return back()->with('success', 'Resolução criada com sucesso.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ResolucaoComplicacao $resolucaoComplicacao)
    {
        $data = $request->validate([
            'nome' => ['required', 'string', 'max:191', 'unique:resolucao_complicacaos,nome,' . $resolucaoComplicacao->id],
            'clavien_dindo_id' => ['nullable', 'integer', 'exists:clavien_dindos,id'],
        ]);

        $resolucaoComplicacao->update($data);

        return back()->with('success', 'Resolução atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ResolucaoComplicacao $resolucaoComplicacao)
    {
        $resolucaoComplicacao->delete();

        return back()->with('success', 'Resolução removida com sucesso.');
    }
}
