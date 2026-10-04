<?php

namespace App\Http\Controllers;

use App\Services\ComplicacaoService;
use App\Services\InternamentoService;
use Illuminate\Http\Request;

/**
 * Listagem completa de internamentos, reservada ao superAdmin. A edição reutiliza
 * `internamentos.update` (que o superAdmin pode sempre usar via Gate::before).
 */
class AdminInternamentoController extends Controller
{
    public function __construct(
        private InternamentoService $service,
        private ComplicacaoService $complicacaoService
    ) {}

    public function index(Request $request)
    {
        abort_unless($request->user()?->hasRole('superAdmin'), 403);

        $filtros = [
            'search' => $request->string('search')->trim()->toString() ?: null,
            'estado' => in_array($request->query('estado'), ['ativos', 'saidos'], true) ? $request->query('estado') : null,
            'localizacao_id' => $request->integer('localizacao_id') ?: null,
            'responsavel_id' => $request->integer('responsavel_id') ?: null,
            'destino_id' => $request->integer('destino_id') ?: null,
            'operado' => in_array($request->query('operado'), ['sim', 'nao'], true) ? $request->query('operado') : null,
            'entrada_de' => $request->date('entrada_de')?->toDateString(),
            'entrada_ate' => $request->date('entrada_ate')?->toDateString(),
        ];

        return inertia('Internamentos/AdminIndex', [
            'internamentos' => $this->service->paginateTodos($filtros),
            'filters' => array_map(fn ($v) => $v ?? '', $filtros),
            'complicacoesOptions' => $this->complicacaoService->getComplicacoesOptions(),
            'resolucoesComplicacaoOptions' => $this->complicacaoService->getResolucoesOptions(),
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
}
