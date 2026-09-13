<?php

namespace App\Http\Controllers;

use App\Services\ComplicacaoService;
use App\Services\InternamentoService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private InternamentoService $service,
        private ComplicacaoService $complicacaoService
    ) {}

    public function index(Request $request)
    {
        $minhaEquipa = $request->boolean('minhaEquipa');

        return inertia('dashboard', [
            'pendentes' => $this->service->getPendentes($request->user()?->equipa, $minhaEquipa),
            'filters' => [
                'minhaEquipa' => $minhaEquipa,
            ],
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
