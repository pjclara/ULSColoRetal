<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInternamentoRequest;
use App\Http\Requests\UpdateInternamentoRequest;
use App\Http\Resources\UtenteResource;
use App\Models\Destino;
use App\Models\Internamento;
use App\Models\OrigemDoInternamento;
use App\Models\User;
use App\Services\ComplicacaoService;
use App\Services\DiagnosticoService;
use App\Services\InternamentoService;
use App\Services\UtenteService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InternamentoController extends Controller
{
    public function __construct(
        private InternamentoService $service,
        private UtenteService $utenteService,
        private DiagnosticoService $diagnosticoService,
        private ComplicacaoService $complicacaoService
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->string('search')->trim()->toString();
        $minhaEquipa = $request->boolean('minhaEquipa');

        $internamentos = $this->service->paginate(
            50,
            $search ?: null,
            $minhaEquipa,
            $request->user()?->id,
        );

        return inertia('Internamentos/Index', [
            'internamentos' => $internamentos,
            'filters' => [
                'search' => $search,
                'minhaEquipa' => $minhaEquipa,
            ],
            'diagnosticosAgrupados' => $this->diagnosticoService->getDiagnosticosAgrupados(),
            'blocoOperatorioOptions' => [
                'tiposDeCirurgia' => $this->service->getTiposDeCirurgia(),
                'tiposDeAbordagem' => $this->service->getTiposDeAbordagem(),
                'reIntervencoesNaoProgramadas' => $this->service->getReIntervencoesNaoProgramadas(),
                'intervencoes' => $this->service->getIntervencoes(),
                'crs' => [
                    'intencoes' => $this->service->getIntencoes(),
                    'estomasDeProtecao' => $this->service->getEstomasDeProtecao(),
                    'locaisExtracaoPeca' => $this->service->getLocaisExtracaoPeca(),
                    'tiposDeDreno' => $this->service->getTiposDeDreno(),
                    'aderencias' => $this->service->getAderencias(),
                    'tiposDeResseccao' => $this->service->getTiposDeResseccao(),
                    'neoplasiasResiduais' => $this->service->getNeoplasiasResiduais(),
                    'perdasHematicas' => $this->service->getPerdasHematicas(),
                ],
                'descricao' => [
                    'anastemoseModos' => $this->service->getAnastemoseModos(),
                    'anastemoseVias' => $this->service->getAnastemoseVias(),
                    'anastemoseSentidos' => $this->service->getAnastemoseSentidos(),
                    'tiposDeReconstrucao' => $this->service->getTiposDeReconstrucao(),
                    'localizacoesAnastemose' => $this->service->getLocalizacoesAnastemose(),
                    'confirmacoesAnastemose' => $this->service->getConfirmacoesAnastemose(),
                    'qualidadesPecaOperatoria' => $this->service->getQualidadesPecaOperatoria(),
                ],
            ],
            'internamentoOptions' => [
                'origensInternamento' => $this->service->getOrigensInternamento(),
                'estadosAlta' => $this->service->getEstadosAlta(),
                'responsaveis' => $this->service->getResponsaveis(),
                'clavienDindo' => $this->service->getClavienDindo(),
                'destinos' => $this->service->getDestinos(),
                'casosSociais' => $this->service->getCasosSociais(),
                'localizacoes' => $this->service->getLocalizacoes(),
                'origensDaReferenciacao' => $this->service->getOrigensDaReferenciacao(),
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
                'origensDaReferenciacao' => $this->service->getOrigensDaReferenciacao(),
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
        $internamento->loadCount('complicacaos');
        $internamento->loadCount('blocoOperatorios');
        $internamento->loadCount('diagnosticos');

        return Inertia::render('Internamentos/Show', [
            'internamento' => $internamento->load([
                'utente',
                'origemDoInternamento',
                'diagnosticos',
                'destino',
                'responsavel',
                'blocoOperatorios',
                'clavienDindo',
                'complicacaos',
            ]),
            'utente' => new UtenteResource($internamento->utente),
            'centroDeReferencia' => $internamento->utente->centroDeReferencia?->load(['origem', 'destino', 'responsavel']),
            'centroDeReferenciaOptions' => [
                'origens' => OrigemDoInternamento::select('id', 'nome')->get(),
                'destinos' => Destino::select('id', 'nome')->get(),
                'responsaveis' => User::select('id', 'name')->get(),
            ],
            'internamentoOptions' => [
                'origensInternamento' => $this->service->getOrigensInternamento(),
                'estadosAlta' => $this->service->getEstadosAlta(),
                'responsaveis' => $this->service->getResponsaveis(),
                'clavienDindo' => $this->service->getClavienDindo(),
                'destinos' => $this->service->getDestinos(),
                'casosSociais' => $this->service->getCasosSociais(),
                'localizacoes' => $this->service->getLocalizacoes(),
                'origensDaReferenciacao' => $this->service->getOrigensDaReferenciacao(),
            ],
             'diagnosticosAgrupados' => $this->diagnosticoService->getDiagnosticosAgrupados(),
             'complicacoesAgrupadas' => $this->complicacaoService->getComplicacoesAgrupadas(),
        ]);
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
        $internamento->delete();

        return back()->with('success', 'Internamento removido com sucesso.');
    }

    public function addDiagnostico(Request $request, Internamento $internamento)
    {
        $request->validate([
            'diagnosticoId' => 'required|exists:diagnosticos,id',
        ]);

        $this->service->addDiagnostico($internamento, $request->input('diagnosticoId'));

        return redirect()
            ->route('internamentos.show', $internamento->id)
            ->with('success', 'Diagnóstico adicionado com sucesso ao internamento.');
    }

    public function removeDiagnostico(Request $request, Internamento $internamento)
    {
        $request->validate([
            'diagnosticoId' => 'required|exists:diagnosticos,id',
        ]);

        $this->service->removeDiagnostico($internamento, $request->input('diagnosticoId'));

        return redirect()
            ->route('internamentos.show', $internamento->id)
            ->with('success', 'Diagnóstico removido com sucesso do internamento.');
    }

    public function addComplicacao(Request $request, Internamento $internamento)
    {
        $request->validate([
            'complicacaoId' => 'required|exists:complicacaos,id',
        ]);

        $this->service->addComplicacao($internamento, $request->input('complicacaoId'));

        return redirect()
            ->route('internamentos.show', $internamento->id)
            ->with('success', 'Complicação adicionada com sucesso ao internamento.');
    }

    public function removeComplicacao(Request $request, Internamento $internamento)
    {
        $request->validate([
            'complicacaoId' => 'required|exists:complicacaos,id',
        ]);

        $this->service->removeComplicacao($internamento, $request->input('complicacaoId'));

        return redirect()
            ->route('internamentos.show', $internamento->id)
            ->with('success', 'Complicação removida com sucesso do internamento.');
    }
}
