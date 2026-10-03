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

    /** Âmbito de doentes a mostrar no dashboard: os meus, os da minha equipa, ou todos. */
    private const AMBITOS = ['meus', 'equipa', 'todos'];

    public function index(Request $request)
    {
        $ambito = $request->input('ambito', 'meus');

        if (!in_array($ambito, self::AMBITOS, true)) {
            $ambito = 'meus';
        }

        return inertia('dashboard', [
            'pendentes' => $this->service->getPendentes($ambito, $request->user()?->equipa, $request->user()?->id),
            'filters' => [
                'ambito' => $ambito,
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
